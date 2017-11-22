<?php

print_r(getSearch());


function getSearch() {

    $handle = fopen("result.txt", "r");
    $data = array();

    while (($read = fgetcsv($handle, 0, ";")) !== FALSE) {

        $data[] = $read;
    }
    fclose($handle);

    return $data;
}
/*
1;100%;71;url=/documents/docs/Book noir agence (Fabien C)/BOOK NOIR Tarifs/source/14-1 TARIF 2017 4FLEET TC4.pdf sample=BAREME DES PRESTATIONS 2017 Tarif en vigueur à partir du 01.01.2017 Tarif 2017 Tourisme & petit utilitaire Pneumatiques DESIGNATION Forfait "montage" Ce forfait comprend ;dépose/pose de la roue démontage/montage du pneu remplacement de la valve caoutchouc mise à la pression équilibrage (masses comprises) serrage à la clé dynamométrique De 10" à 16" De 17" et ou Runflat Forfait permutation Sécurité 2 roues Ce forfait comprend dépose/pose des 2 roues permutation équilibrage ... caption=TARIF 2017 4FLEET TC4 author=NGI4488 type=application/pdf modtime=1510665152 size=521743
*/


function simpleSearch () {

    include "xapian.php";

    // Open the database for searching.
    try {
        $database = new XapianDatabase($_GET['D']);

        // Start an enquire session.
        $enquire = new XapianEnquire($database);

        $query_string = $_GET['Q'];

        $qp = new XapianQueryParser();
        $stemmer = new XapianStem("french");
      
        $qp->set_stemmer($stemmer);
        $qp->set_database($database);
        $qp->set_stemming_strategy(XapianQueryParser::STEM_SOME);

        $query = $qp->parse_query($query_string);
        print "Parsed query is: {$query->get_description()}\n";

        // Find the top 100 results for the query.
        $enquire->set_query($query);
        $matches = $enquire->get_mset(0, 100);

        // Display the results.
        print "{$matches->get_matches_estimated()} results found:<br/><br/>";

        foreach ($matches->begin() as $i => $docid) {
    	  $n = $i->get_rank() + 1;
    	  $data = $i->get_document()->get_data();
          print "<B>$n</B>: {$i->get_percent()}% docid=$docid<br/> [".urldecode($data)."]<br/>";

    	  // fput("{$n};{$i->get_percent()}%;{$docid};".urldecode($data)); //$fields ? et attention ';' pb champs [sample=]
        }
    } catch (Exception $e) {
        print $e->getMessage() . "\n";
        exit(1);
    }
}

?>
