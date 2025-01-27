<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auxiliar_m extends CI_model {
	
	function __Construct(){
	   parent::__construct();
	}
	
	function auxiliares(){
	$sql= "select trim(codaux) as codaux, trim(nomaux) as nomaux
		      from cntaux where trim(codaux)!='' order by codaux";
	  $res = $this->db->query($sql);
	  return ($res->num_rows() >0)? $res->result_array():false;	
	}
	
	//infromacion auxiliares 
	function informacionAuxiliares(){
	    $sql= "select trim(codaux) as codaux, trim(nomaux) as nombre
		      from cntaux where trim(codaux)!='' order by codaux";
	  $res = $this->db->query($sql);
	  return ($res->num_rows() >0)? $res->result_array():false;	
	}
	
function nom_cc($codcc){
		$sql = "select nomcts from cntcts where trim(codcts)=?";
		$res = $this->db->query($sql,array(trim($codcc)));
		if($res->num_rows()>0){
			return $res->row_array();;
		}else{
			return false;	
		}	
	}
	
	function cuentas($fecha1,$fecha2,$codctaa,$codctab,$codtrca,$codtrcb,$codctsa,$codctsb,$codauxa,$codauxb){
		
		$sql = "SELECT DISTINCT(codcta) codcta FROM view_sirco_detalle WHERE trim(stdcmp)='Generado' AND codcta != 'X' 
		AND (fchcmp BETWEEN ? AND ?)";
		if($codctaa != 0){$sql .= " AND codcta >= '$codctaa'";}
		if($codctab != 0){$sql .= " AND codcta <= '$codctab'";}
		if($codtrca != 0){$sql .= " AND codtrc >= '$codtrca'";}
		if($codtrcb != 0){$sql .= " AND codtrc <= '$codtrcb'";}
		if($codctsa != 0){$sql .= " AND codcts >= '$codctsa'";}
		if($codctsb != 0){$sql .= " AND codcts <= '$codctsb'";}
		if($codauxa != 0){$sql .= " AND codaux >= '$codauxa'";}
		if($codauxb != 0){$sql .= " AND codaux <= '$codauxb'";}
		$sql .=" ORDER BY codcta";	
		$res = $this->db->query($sql,array($fecha1,$fecha2));
		//echo $this->db->last_query();
		//exit();
		if($res->num_rows() > 0){
			return $res->result_array();
		}else{return false;}
	}
	
	function obtener_saldos_cuentas_mes($cuenta,$periodo,$campo){
		$sql = "select sld0$campo saldo from view_sirco_cntsld_res  where trim(codcta)=? and agncnt=?";	
		$res = $this->db->query($sql,array($cuenta,$periodo));
		//echo $this->db->last_query();
		//exit();
		if($res->num_rows()>0){
			$saldoActual = $res->result_array();
			$saldo = $saldoActual[0]['saldo'];
			return intval($saldo);
		}else{
			return false;	
		}
	}
	
	
	function saldos_cuentas_tercero_mes($cuenta,$periodo,$campo,$codtrc){
		$sql = "select sld0$campo saldo from view_sirco_cntcpa_res  where trim(codcta)=? and agncnt=? and codtrc=?";	
		$res = $this->db->query($sql,array($cuenta,$periodo,$codtrc));
		//echo $this->db->last_query();
		//exit();
		if($res->num_rows()>0){
			$saldoActual = $res->result_array();
			$saldo = $saldoActual[0]['saldo'];
			return intval($saldo);
		}else{
			return false;	
		}
	}
	
	function nombre_cuenta($cod){
		$sql = "select nomcta from cntcta where trim(codcta)=?";
		$res = $this->db->query($sql,array(trim($cod)));
		//echo $this->db->last_query();
		//exit();
		if($res->num_rows()>0){
			return $res->row_array();;
		}else{
			return false;	
		}	
	}
	
	function detalle($fecha1,$fecha2,$codcta,$codtrca,$codtrcb,$codctsa,$codctsb,$codauxa,$codauxb){
		
		$sql = "SELECT fchcmp fch, fchcmp ,codsed ,coddoc , view_sirco_detalle.nrocmp, nomtrc, codcts, debdcm, credcm, detdcm, ' CC:'||codcts||' ACRATE:'||fchpgs||' RC:'||rcbkja as actpgs 
			    FROM view_sirco_detalle left join actpgs on agncnt||mescnt||coddoc||view_sirco_detalle.nrocmp=nrotrn
			    WHERE trim(stdcmp)='Generado' AND (fchcmp BETWEEN ? AND ?) AND trim(codcta)=?";
			
		if($codtrca != 0){$sql .= " AND codtrc >= '$codtrca' AND codtrc <= '$codtrcb'";}
		if($codctsa != 0){$sql .= " AND codcts >= '$codctsa' AND codcts <= '$codctsb'";}
		if($codauxa != 0){$sql .= " AND codaux >= '$codauxa' AND codaux<= '$codauxb'";}
		$sql .=" ORDER BY fch, coddoc, nrocmp";	
			$res = $this->db->query($sql,array($fecha1,$fecha2,$codcta));
			//echo $this->db->last_query();
		    //exit();
		
		if($res->num_rows() > 0){
			return $res->result_array();
		}else{return false;}
	}
}
?>