<?php
class data extends CI_Model{
	var $tabel1 = 'jurnal';
	var $tabel2 = 'direktori';

	function getData() {
    return $this->db->get($this->tabel1);
  }

  function getJurnal() {
    return $this->db->get($this->tabel2);
  }
  function getJudulJurnal($id){
    return $this->db->
            select('judul,deskriptor')->
            from('direktori')->
            where('id_direktori', $id)->
            get()->row_array();

  }
  function getArtikel(){
    $this->db->select('id_jurnal,id_direktori,title,sari,keywords');
    $this->db->from('jurnal');
    $this->db->where('sari !=',"");
    $this->db->where('sari !=',"null");
    $this->db->where('keywords !=',"null");
    $this->db->where('keywords !=',"");
    return $this->db->get();
  }
}
?>