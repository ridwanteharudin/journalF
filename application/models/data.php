<?php
class data extends CI_Model{
	var $tabel1 = 'artikel';
	var $tabel2 = 'jurnal';

	function getData() {
    return $this->db->get($this->tabel1);
  }

  function getJurnal() {
    return $this->db->get($this->tabel2);
  }
  function getJudulJurnal($id){
  	return $this->db->
  					select('judul_jurnal')->
    				from('jurnal')->
    				where('id_jurnal', $id)->
    				get()->row_array();

  }
}
?>