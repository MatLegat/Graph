<?php
    /*
    Vertex structure of my implementation of a graph structure in php.
    Author: Matteus Legat
    Created on: November, 8, 2015
    */

    header("Content-type: text/html; charset=utf-8");
    include_once 'Markable.php';

    class Vertex extends Markable {

        // Id única e rótulo deste vértice:
        protected $_id, $_label;
        // Vértices sucessores deste:
        protected $_successors = array();
        // Vértices antecessores deste:
        protected $_predecessors = array();
        // Vértices adjacentes deste:
        protected $_adjacents = array();

        /*
        Censtrutor: Constrói o vértice com id e label informados.
        */
        function __construct($id, $label, $is_directed) {
            // Checa tipos dos parâmetros:
            if (!is_int($id))
                throw new Exception('$id em Vertex() deve ser um inteiro');

            $this->_id = $id;
            $this->_label = $label;
            // Se o grafo é não orientado,
            // adjacentes, antecessores e sucessores são o mesmo:
            if (!$is_directed)
                $this->_successors = &$this->_adjacents;  // Mesmo endereço.
                $this->_predecessors = &$this->_adjacents;  // Mesmo endereço.
        }

        /*
        Retorna id do vértice.
        */
        function getId() {
            return $this->_id;
        }

        /*
        Retorna label do vértice.
        */
        function getLabel() {
            return $this->_label;
        }

        /*
        Adiciona um vértice ao array de sucessores deste vértice.
        */
        function addSuccesor(&$vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em addSuccesor() deve ser um Vértice');

            $this->_successors[$vertex->getId()] = $vertex;
            $this->_adjacents[$vertex->getId()] = $vertex;
            // OBS: caso seja orientado, readiciona na mesma lista, na mesma posição.
        }

        /*
        Adiciona um vértice ao array de antecessores deste vértice.
        */
        function addPredecesor(&$vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em addPredecesor() deve ser um Vértice');

            $this->_predecessors[$vertex->getId()] = $vertex;
            $this->_adjacents[$vertex->getId()] = $vertex;
            // OBS: caso seja orientado, readiciona na mesma lista, na mesma posição.
        }

        /*
        Remove um vértice do array de sucessores deste vértice.
        */
        function removeSuccesor(&$vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em removeSuccesor() deve ser um Vértice');

            unset($this->_successors[$vertex->getId()]);
        }

        /*
        Remove um vértice do array de antecessores deste vértice.
        */
        function removePredecesor($vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em removePredecesor() deve ser um Vértice');

            unset($this->_predecessors[$vertex->getId()]);
        }

        /*
        Remove um vértice do array de adjacentes deste vértice.
        */
        function removeAdjacent($vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em removePredecesor() deve ser um Vértice');

            unset($this->_adjacents[$vertex->getId()]);
        }


        /*
        Retorna array com todos os sucessores deste vértice.
        */
        function getSuccessors() {
            return $this->_successors;
        }

        /*
        Retorna array com todos os antecessores deste vértice.
        */
        function getPredecessors() {
            return $this->_predecessors;
        }

        /*
        Retorna array com todos os adjacentes deste vértice.
        */
        function getAdjacents() {
            return $this->_adjacents;
        }

        /*
        Informa se um vertice é sucessor deste.
        */
        function isSuccessor($vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em isSuccesor() deve ser um Vértice');

            return array_key_exists($vertex->getId(), $this->_successors);
        }

        /*
        Informa se um vertice é antecessor deste.
        */
        function isPredecessor($vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em isPredecessor() deve ser um Vértice');

            return array_key_exists($vertex->getId(), $this->_predecessors);
        }

        /*
        Informa se um vertice é adjacente deste.
        */
        function isAdjacent($vertex) {
            // Checa tipos dos parâmetros:
            if (!is_a($vertex, 'Vertex'))
                throw new Exception('$vertex em isPredecessor() deve ser um Vértice');

            return array_key_exists($vertex->getId(), $this->_adjacents);
        }

        /*
        Informa grau do vértice.
        */
        function getDegree($is_directed) {
            if ($is_directed)
                return $this->getEmissionDegree() + $this->getReceptionDegree();
            else
                return $this->getEmissionDegree();
        }

        /*
        Informa grau de emissão do vértice.
        */
        function getEmissionDegree() {
            return count($this->_successors);
        }

        /*
        Informa grau de recepção do vértice.
        */
        function getReceptionDegree() {
            return count($this->_predecessors);
        }

    }

?>
