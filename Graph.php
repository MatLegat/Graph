<?php
	/*
	My implementation of a graph structure in php.
	Note: it does not works with multigraphs (for a better complexity),
			neither with hypergraphs.
	Author: Matteus Legat
	Created on: November, 8, 2015
	*/

	header("Content-type: text/html; charset=utf-8");
	include_once 'Vertex.php';
	include_once 'Edge.php';
	include_once 'aux_functions.php';

	/*
	Chamadas dos testes:
	*/
	test1();

	class Graph {

		// Array contendo todos os vértices:
		protected $_vertexes = array();
		// Array contendo todas as arestas:
		protected $_edges = array();
		// Mapa para se obter posição do vértice com o seu rótulo:
		protected $_label_mapper = array();
		// Boolean que identifica se o grafo é ou não direcionado:
		protected $_is_directed;
		// Armazena valor para ser usado na id do próximo vértice:
		protected $_next_id = 0;

		/*
		Construtor: Cria um grafo direcionado ou não com um único vértice,
		podendo definir um rótulo para este vértice.
		*/
		function __construct($is_directed, $first_vertex_label = NULL) {
			// Checa tipos dos parâmetros:
			if (!is_bool($is_directed))
				throw new Exception('$is_directed em Graph() deve ser um booleano');

			$this->_is_directed = $is_directed;
			$this->createVertex($first_vertex_label);
		}

		/*
		Cria um vértice, podendo definir um rótulo a ele.
		*/
		function createVertex($label = NULL) {
			$id = $this->_next_id++;
			$this->_vertexes[$id] = new Vertex($id, $label, $this->_is_directed);
			if ($label != NULL) {
				if (array_key_exists($label, $this->_label_mapper))
					echo "AVISO: Já existe um vértice com o mesmo rótulo. " .
							"Ao obter um vértice por este rótulo, " .
							"será retornado o mais recentemente criado.\n";
				$this->_label_mapper[$label] = $id;
			}
		}

		/*
		Remove um vértice do grafo.
		*/
		function removeVertex($vertex) {
			// Checa tipos dos parâmetros:
			if (!is_a($vertex, 'Vertex'))
					throw new Exception('$vertex em removeVertex() deve ser um Vértice');

			// Desconecta de todos os sucessores:
			foreach ($vertex->getSuccessors() as $successor) {
				$this->disconnect($vertex, $successor);
			}
			// Desconecta de todos os antecessores:
			foreach ($vertex->getPredecessors() as $predecessor) {
				$this->disconnect($predecessor, $vertex);
			}
			// Remove label (caso exista) do mapa de labels:
			unset($this->_label_mapper[$vertex->getLabel()]);
			// Remove do array de vértices do grafo:
			unset($this->_vertexes[$vertex->getId()]);
		}

		/*
		Cria uma aresta conectando os dois vértices,
		definindo um peso (1 por padrão) à aresta.
		*/
		function connect($vertex_1, $vertex_2, $weight = 1) {
			// Checa tipos dos parâmetros:
			if (!is_int($weight))
				throw new Exception('$weight em connect() deve ser um inteiro');
			if (!is_a($vertex_1, 'Vertex'))
					throw new Exception('$vertex_1 em connect() deve ser um Vértice');
			if (!is_a($vertex_2, 'Vertex'))
					throw new Exception('$vertex_2 em connect() deve ser um Vértice');

			$edge_id = $this->generateEdgeId($vertex_1, $vertex_2);
			if (!$this->_is_directed) {
				$edge_id_r = $this->generateEdgeId($vertex_2, $vertex_1);
				if (array_key_exists($edge_id_r , $this->_edges)) {
					// Se for orientado e já estão conectados na ordem inversa:
					$edge_id = $edge_id_r;
					// Insere na ordem já inserida (para atualizar peso);
				}
			}
			// Adiciona no array de arestas do grafo:
			$this->_edges[$edge_id] =
					new Edge($vertex_1, $vertex_2, $weight);
			// Adiciona 2 no array de sucessores de 1:
			$vertex_1->addSuccesor($vertex_2);
			// Adiciona 1 no array de antecessores de 2:
			$vertex_2->addPredecesor($vertex_1);
			// OBS: se for nao orientado, os arrays de antecessores e sucessores
			// de cada grafo são o mesmo (mesmo endereço na memória).
		}

		/*
		Remove aresta entre os dois vértices.
		*/
		function disconnect($vertex_1, $vertex_2) {
			// Checa tipos dos parâmetros:
			if (!is_a($vertex_1, 'Vertex'))
					throw new Exception('$vertex_1 em disconnect() deve ser um Vértice');
			if (!is_a($vertex_2, 'Vertex'))
					throw new Exception('$vertex_2 em disconnect() deve ser um Vértice');

			$edge_id = $this->generateEdgeId($vertex_1, $vertex_2);
			// Remove do array de arestas do grafo:
			unset($this->_edges[$edge_id]);
			if (!$this->_is_directed) {
				// Se é não orientado e está conectado na ordem inversa:
				// Remove a conexão "inversa" do array de arestas do grafo:
				$edge_id_r = $this->generateEdgeId($vertex_2, $vertex_1);
				unset($this->_edges[$edge_id_r]);
			} else {
				// Se é orientado e não está conectado na ordem inversa:
				if (!$this->isConnected($vertex_2, $vertex_1)) {
					// Remove 1 do array de adjacentes de 2, e vice versa:
					$vertex_1->removeAdjacent($vertex_2);
					$vertex_2->removeAdjacent($vertex_1);
				}
			}
			// Remove 2 do array de sucessores de 1:
			$vertex_1->removeSuccesor($vertex_2);
			// Remove 1 do array de antecessores de 2
			$vertex_2->removePredecesor($vertex_1);
			// OBS: se for nao orientado, os arrays de antecessores e sucessores
			// e adjacentes de cada grafo são o mesmo (mesmo endereço na memória).
		}

		/*
		Informa se dois vértices estão conectados, ou seja, se existe uma aresta
		entre eles (no sentido informado, caso o grafo seja orientado).
		*/
		function isConnected($vertex_1, $vertex_2) {
			// Checa tipos dos parâmetros:
			if (!is_a($vertex_1, 'Vertex'))
					throw new Exception('$vertex_1 em isConnected() deve ser um Vértice');
			if (!is_a($vertex_2, 'Vertex'))
					throw new Exception('$vertex_2 em isConnected() deve ser um Vértice');

			return ($this->getEdge($vertex_1, $vertex_2) != NULL);
		}

		/*
		Retorna a aresta entre os dois vértices.
		*/
		function getEdge($vertex_1, $vertex_2) {
			// Checa tipos dos parâmetros:
			if (!is_a($vertex_1, 'Vertex'))
					throw new Exception('$vertex_1 em getEdge() deve ser um Vértice');
			if (!is_a($vertex_2, 'Vertex'))
					throw new Exception('$vertex_2 em getEdge() deve ser um Vértice');

			$edge_id = $this->generateEdgeId($vertex_1, $vertex_2);
			if ($this->_is_directed) {
				if (array_key_exists($edge_id , $this->_edges))
					return $this->_edges[$edge_id];
			} else {
				if (array_key_exists($edge_id , $this->_edges)) {
					return $this->_edges[$edge_id];
				// Se for não orientado, pode ter sido conectado na ordem inversa:
				} else {
					$edge_id_r = $this->generateEdgeId($vertex_2, $vertex_1);
					if (array_key_exists($edge_id_r , $this->_edges))
						return $this->_edges[$edge_id_r];
				}
			// Os vértices não estão conectados:
			}
			return NULL;
		}

		/*
		Retorna vértice com a id informada.
		*/
		function getVertexById($vertex_id) {
			// Checa tipos dos parâmetros:
			if (!is_int($vertex_id))
				throw new Exception('$vertex_id em getVertexById() deve ser um inteiro');

			return $this->_vertexes[$vertex_id];
		}

		/*
		Retorna vértice com o label informado.
		*/
		function getVertexByLabel($vertex_label) {
			return $this->getVertexById($this->_label_mapper[$vertex_label]);
		}

		/*
		Retorna array com todos os vértices.
		*/
		function getVertexes() {
			return $this->_vertexes();
		}

		/*
		Retorna array com todas as arestas.
		*/
		function getEdges() {
			return $this->_edges();
		}

		/*
		Retorna array com todos os vértices adjacentes aou informado
		*/
		function getAdjacents($vertex) {
			// Checa tipos dos parâmetros:
			if (!is_a($vertex, 'Vertex'))
					throw new Exception('$vertex em getAdjacents() deve ser um Vértice');

			return $vertex->getAdjacents();
		}

		/*
		Retorna a ordem do grafo.
		*/
		function getOrder() {
			return count($this->_vertexes);
		}

		/*
		Retorna o grau de um vértice.
		*/
		function getDegree($vertex) {
			// Checa tipos dos parâmetros:
			if (!is_a($vertex, 'Vertex'))
					throw new Exception('$vertex em getDegree() deve ser um Vértice');

			return $vertex->getDegree($this->_is_directed);
		}

		/*
		Gera um identificador de aresta de acordo com o id dos vértices.
		*/
		function generateEdgeId($vertex_1, $vertex_2) {
			// Checa tipos dos parâmetros:
			if (!is_a($vertex_1, 'Vertex'))
						throw new Exception('$vertex_2 em generateEdgeId() deve ser um Vértice');
			if (!is_a($vertex_2, 'Vertex'))
					throw new Exception('$vertex_1 em generateEdgeId() deve ser um Vértice');

			$vertex_1_id = $vertex_1->getId();
			$vertex_2_id = $vertex_2->getId();
			return $vertex_1_id . '#' . $vertex_2_id;

		}

		//Faltam funções complexas!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

	}

?>
