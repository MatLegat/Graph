<?php
    /*
    Class to allow marks in my implementation of a graph structure in php.
    Author: Matteus Legat
    Created on: November, 8, 2015
    */

    header("Content-type: text/html; charset=utf-8");

    class Markable {

        protected $_marks = array();

        /*
        Adiciona marca com id informada ao vértice ou aresta.
        */
        function mark($mark_id) {
            $this->_marks[$mark_id] = true;
        }

        /*
        Remove marca com id informada do vértice ou aresta.
        */
        function unmark($mark_id) {
            unset($this->_marks[$mark_id]);
        }

        /*
        Verifica se o vértice ou aresta possui marca com id informada.
        */
        function hasMark($mark_id) {
            return array_key_exists($mark_id , $this->_marks);
        }

    }

?>
