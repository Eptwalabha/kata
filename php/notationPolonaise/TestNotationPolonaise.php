<?php

/**
 * Kata de la calculette polonaise.
 */
class TestNotationPolonaise extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function retourne_l_expression_si_non_valide() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("0 + 0");

        $this->assertEquals("0 + 0", $result);
    }

    /**
     * @test
     */
    public function retourne_0_pour_l_addition_plus_0_0() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("+ 0 0");

        $this->assertEquals(0, $result);
    }

    /**
     * @test
     */
    public function retourne_la_somme_de_l_addition_de_deux_entiers_positifs() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("+ 3 4");

        $this->assertEquals(7, $result);
    }

    /**
     * @test
     */
    public function retourne_la_somme_de_l_addition_de_deux_reels_positifs() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("+ 3.5 -4.2");

        $this->assertEquals(-0.7, $result);
    }

    /**
     * @test
     */
    public function retourne_la_somme_de_l_addition_de_deux_entiers_negatifs() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("+ -3 -4");

        $this->assertEquals(-7, $result);
    }

    /**
     * @test
     */
    public function retourne_le_reste_de_la_soustraction_de_deux_entiers_quelconque() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("- 3 -4");

        $this->assertEquals(7, $result);
    }

    /**
     * @test
     */
    public function retourne_le_produit_de_la_multiplication_de_deux_entiers_quelconque() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("* 6 -3");

        $this->assertEquals(-18, $result);
    }

    /**
     * @test
     */
    public function retourne_le_resultat_de_la_division_de_deux_entiers_quelconque() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("/ 5 2");

        $this->assertEquals(2.5, $result);
    }

    /**
     * @test
     */
    public function retourne_la_somme_d_une_expression_et_un_nombre() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("+ - 5 2 6.5");

        $this->assertEquals(((5 - 2) + 6.5), $result);
    }

    /**
     * @test
     */
    public function retourne_le_produit_de_deux_expressions() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("* - 5 2 / 6.5 6.5");

        $this->assertEquals(((5 - 2) * (6.5 / 6.5)), $result);
    }

    /**
     * @test
     */
    public function retourne_le_resultat_d_une_expression_compliquee() {
        $calculette = new PolishCalculator();

        $result = $calculette->calcul("- * / 15 - 7 + 1 1 3 + 2 + 1 1");

        $this->assertEquals(5, $result);
    }

}

class PolishCalculator {

    private $operations;
    private $pattern;

    public function __construct() {
        $this->operations = array();
        $this->operations['+'] = function($a, $b) {
            return $a + $b;
        };
        $this->operations['-'] = function($a, $b) {
            return $a - $b;
        };
        $this->operations['*'] = function($a, $b) {
            return $a * $b;
        };
        $this->operations['/'] = function($a, $b) {
            return $a / $b;
        };
        $this->pattern = '/(?P<operator>[\+\-\*\/]) (?P<a>-?\d+\.?[0-9]*) (?P<b>-?\d+\.?[0-9]*)/';
    }

    public function calcul($expression) {
        while (preg_match($this->pattern, $expression)) {
            $expression = preg_replace_callback($this->pattern, function($matches) {
                return $this->operations[$matches['operator']]($matches['a'], $matches['b']);
            }, $expression);
        }
        return $expression;
    }
}
