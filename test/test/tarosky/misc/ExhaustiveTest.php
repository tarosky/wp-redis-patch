<?php

use Eris\Generator;
use Eris\TestTrait;

class ExhaustiveTest extends ExhaustiveOCTestCase {
    use TestTrait;

    private static function createOCGenerator() {
        return Generator\bind(
            Generator\elements([0, 2, 4, 6]),
            function ($size) {
                return Generator\vector($size, Generator\nat());
            }
        );
    }

    private static function ordering($indexes) {
        $values = [];
        for ($i = 0; $i < count($indexes); $i++) {
            for ($j = 0; $j < count($indexes[$i]); $j++) {
                $values[] = [
                    $i,
                    $indexes[$i][$j],
                ];
            }
        }
        usort($values, function($a, $b) {
            return $a[1] - $b[1];
        });
        $res = [];
        foreach ($values as $v) {
            $res[] = $v[0];
        }

        return $res;
    }

    public function testEmptyPrecondition() {
        $this->forAll(
            Generator\tuple(
                self::createOCGenerator(),
                self::createOCGenerator(),
                self::createOCGenerator(),
            )
        )->disableShrinking()->then(function($indexes) {
            $order_list = self::ordering($indexes);
            $ocstates = array_fill(0, 3, true);

            if (count($order_list) == 0) {
                return;
            }

            self::$redis->flushdb();
            $this->ocs = [
                new TaroskyObjectCache(),
                new TaroskyObjectCache(),
                new TaroskyObjectCache(),
            ];
            foreach ($order_list as $i) {
                if ($ocstates[$i]) {
                    $this->ocs[$i]->get(self::KEY, '', true);
                } else {
                    $this->ocs[$i]->add(self::KEY, self::VAL);
                }
                $ocstates[$i] = !$ocstates[$i];
            }

            try {
                foreach (range(0, 2) as $i) {
                    $this->assertEquals(self::VAL, $this->ocs[$i]->get(self::KEY, '', true));
                }
            } catch (Exception $e) {
                error_log('failed order: ' . var_export($order_list, true));
                throw $e;
            }
        });
    }
}
