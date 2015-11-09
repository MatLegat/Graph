<?php
    /*
    Edge structure of my implementation of a graph structure in php.
    Author: Matteus Legat
    Created on: November, 8, 2015
    */

    header("Content-type: text/html; charset=utf-8");
    include_once 'Markable.php';

    class Edge extends Markable {

        // Vértices pertencentes à aresta:
        protected $_vertexes;
        // Peso da aresta
        protected $_weight;

        /*
        Construtor: constrói vértice com um peso entre os dois elementos.
        */
        function __construct(&$vertex_1, &$vertex_2, $weight) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex_1, 'Vertex'))
                throw new Exception('$vertex_1 em Edge() deve ser um Vértice');
            if (!is_a($vertex_2, 'Vertex'))
                throw new Exception('$vertex_2_id em Edge() deve ser um Vértice');
            if (!is_int($weight))
                throw new Exception('$weight em Edge() deve ser um inteiro');

            $this->_vertexes[1] = $vertex_1;
            $this->_vertexes[2] = $vertex_2;
            $this->_weight = $weight;
        }

        /*
        Retorna vértice 1 da aresta.
        */
        function getVertex1() {
            return $this->_vertexes[1];
        }

        /*
        Retorna vértice 2 da aresta.
        */
        function getVertex2() {
            return $this->_vertexes[2];
        }

        /*
        Retorna peso da aresta.
        */
        function getWeight() {
            return $this->_weight;
        }

    }

?>
