<?php 

include '../pg_functions.php';
include '../config.php';

// Récup des variables 
$territoire = $_GET['territoire'];
$territoire_token = $_GET['territoire_token'];
$territoire_sql = $_GET['territoire_sql']; 

$territoire_lon = $_GET['territoire_lon'];
$territoire_lat = $_GET['territoire_lat'];
$territoire_rad = $_GET['territoire_rad'];

// Filtre spatial en fonction radius / territoire
if ($territoire_sql == "") {
    // $filtre_geo = " LEFT JOIN cigale.liste_entites_admin as b ON st_intersects(a.geom, b.geom) WHERE b.texte = '".$territoire."' and b.valeur = '".$territoire_token."' ";
    $filtre_geo = " LEFT JOIN referentiel.entites as b ON st_intersects(a.geom, b.geom) WHERE b.lib_entite = '".$territoire."' and b.code_entite = '".$territoire_token."' ";
    // $filtre_geo_pos = " LEFT JOIN cigale.liste_entites_admin as b ON st_intersects(st_pointonsurface(a.geom), b.geom) WHERE b.texte = '".$territoire."' and b.valeur = '".$territoire_token."' ";
    $filtre_geo_pos = " LEFT JOIN referentiel.entites as b ON st_intersects(st_pointonsurface(a.geom), b.geom) WHERE b.lib_entite = '".$territoire."' and b.code_entite = '".$territoire_token."' ";
    $where_geo = "";
} else {
    $filtre_geo = " LEFT JOIN (".$territoire_sql.") as b ON st_intersects(a.geom, b.geom) ";
    $filtre_geo_pos = " LEFT JOIN (".$territoire_sql.") as b ON st_intersects(a.geom, b.geom) ";
    $where_geo = "WHERE b.geom IS NOT NULL";
};


// $sql_gisements_graph = "
// SELECT groupe_rom, sum(val_t_net_calcule) AS mob_t, sum(val_m3ch4_net_calcule) AS mob_mwh
// FROM geres.rom_bilan_comm AS a 
// LEFT JOIN geres.rom_tpk_param AS aa USING (id_param)
// WHERE id_comm IN (
    // SELECT DISTINCT id_comm 
    // FROM commun.tpk_commune_2018 AS a
        // ".$filtre_geo."
        // ".$where_geo." 
// )
// GROUP BY groupe_rom
// ";


$sql_gisements_graph = "
SELECT 
	groupe_rom as name, 
	-- '' as color,
	sum(val_t_net_calcule)::integer AS y
	-- sum(val_m3ch4_net_calcule) AS mob_mwh
FROM geres.rom_bilan_comm AS a 
LEFT JOIN geres.rom_tpk_param AS aa USING (id_param)
WHERE id_comm IN (
    SELECT DISTINCT id_comm 
    FROM commun.tpk_commune_2018 AS a
        ".$filtre_geo."
        ".$where_geo." 
)
GROUP BY groupe_rom
";

// echo(nl2br($sql_alp));

// $to_execute = array($sql_mob_t,$sql_mob_mwh,$sql_territoire_poly, $sql_nb_unites_metha, $sql_gisements, $sql_gisements_graph, $sql_reseau_gaz_5km, $sql_reseau_gaz_5_10km, $sql_iaa, $sql_compostage, $sql_alp);
// execute_sql_multi($to_execute);
execute_sql($sql_gisements_graph);
?>