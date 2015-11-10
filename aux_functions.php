<?php
    /*
    Functions to test my implementation of a graph structure in php.
    Author: Matteus Legat
    Created on: November, 8, 2015
    */

    header("Content-type: text/html; charset=utf-8");

    /*
    Pega entrada na linha de comando.
    */
    function getInput() {
        $input_file = fopen("php://stdin","r");
        $input_line = fgets($input_file);
        fclose($input_file);
        return $input_line;
    }

    /*
    Testes realizados com a estrutura.
    */
    function test1() {
        for($k = 0; $k < 3; $k++) {
            $is_directed = (bool) $k;
            $graph = new Graph($is_directed);
            $graph->createVertex('POTATO');
            $graph->createVertex('ORANGE');
            $graph->createVertex('POTATO');
            $graph->connect($graph->getVertexById(2), $graph->getVertexById(3), 12);
            //$graph->getVertexByLabel('ORANGE')->mark("TAG 1");
            $graph->connect($graph->getVertexById(3), $graph->getVertexById(0));
            $graph->disconnect($graph->getVertexById(0), $graph->getVertexByLabel('POTATO'));
            var_dump($graph->hasEdge($graph->getVertexById(3), $graph->getVertexById(0)));
            // var_dump($graph->getEdge($graph->getVertexById(2), $graph->getVertexById(3)));
            if ($k == 2) {
                $graph->removeVertex($graph->getVertexById(3));
                $graph->connect($graph->getVertexById(0), $graph->getVertexById(1));
            }
            //var_dump($graph);
            printGraph($graph);
            echo "::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::\n";
        }
    }

    function test2() {
        $graph = new Graph(true, 'a');
        $graph->createVertex('b');
        $graph->connect($graph->getVertexByLabel('a'), $graph->getVertexByLabel('a'));
        printProperties($graph);
        $graph->disconnect($graph->getVertexByLabel('a'), $graph->getVertexByLabel('a'));
        $graph->connect($graph->getVertexByLabel('a'), $graph->getVertexByLabel('b'));
        printProperties($graph);
        $graph->createVertex('c');
        $graph->createVertex('d');
        $graph->createVertex('e');
        $graph->removeVertex($graph->getVertexByLabel('a'));
        $graph->connect($graph->getVertexByLabel('b'), $graph->getVertexByLabel('c'));
        $graph->connect($graph->getVertexByLabel('c'), $graph->getVertexByLabel('d'));
        $graph->connect($graph->getVertexByLabel('d'), $graph->getVertexByLabel('e'));
        $graph->connect($graph->getVertexByLabel('e'), $graph->getVertexByLabel('d'));
        printProperties($graph);
        $graph->disconnect($graph->getVertexByLabel('e'), $graph->getVertexByLabel('d'));
        printProperties($graph);
        $graph = new graph(false, 'a');
        $graph->createVertex('b');
        $graph->createVertex('c');
        $graph->createVertex('d');
        $graph->connect($graph->getVertexByLabel('a'), $graph->getVertexByLabel('b'));
        $graph->connect($graph->getVertexByLabel('a'), $graph->getVertexByLabel('c'));
        $graph->connect($graph->getVertexByLabel('a'), $graph->getVertexByLabel('d'));
        $graph->connect($graph->getVertexByLabel('b'), $graph->getVertexByLabel('c'));
        $graph->connect($graph->getVertexByLabel('b'), $graph->getVertexByLabel('d'));
        $graph->connect($graph->getVertexByLabel('c'), $graph->getVertexByLabel('d'));
        printProperties($graph);
        //$graph->connect($graph->getVertexByLabel('b'), $graph->getVertexByLabel('b'));
        $graph->disconnect($graph->getVertexByLabel('c'), $graph->getVertexByLabel('d'));
        printProperties($graph);
    }

    function printProperties($graph) {
        printGraph($graph);
        echo "É orientado: ";
        var_dump($graph->isDirected());
        echo "Ordem: ";
        var_dump($graph->getOrder());
        echo "É regular: ";
        var_dump($graph->isRegular());
        echo "É completo: ";
        var_dump($graph->isComplete());
        echo "É conexo: ";
        var_dump($graph->isConnected());
        echo "É árvore: ";
        var_dump($graph->isTree());
        echo "::::::::::::::::::::::::::::::\n";
    }

    /*
    Imprime todos os vértices e arestas do grafo.
    */
    function printGraph($graph) {
        echo "Vértices:\n";
        foreach ($graph->getVertexes() as $vertex) {
            echo "\t" . $vertex->getId() . " " . $vertex->getLabel() . "\n";
        }
        echo "Arestas:\n";
        foreach ($graph->getEdges() as $edge) {
            echo "\t" . $edge->getVertex1()->getId() . " ---" . $edge->getWeight() . "---";
            if ($graph->isDirected())
                echo ">";
            echo " " . $edge->getVertex2()->getId() . "\n";
        }
    }

?>
