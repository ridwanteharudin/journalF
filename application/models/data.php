<?php
class data extends CI_Model{
  var $tabel1 = 'jurnal';
  var $tabel2 = 'direktori';
  var $tabel3 = 'stemmer';

  function getData() {
    return $this->db->get($this->tabel1);
  }

  function getJurnalAll() {
        
    $this->db->where('id_direktori !=',"");
    $this->db->where('id_direktori !=',"null");
    return $this->db->get($this->tabel2);
  }
  function getJurnal($id){
    return $this->db->
            select('editor,penerbit, alamat,judul,deskriptor')->
            from('direktori')->
            where('id_direktori', $id)->
            get()->row_array();

  }
  function getArtikel(){
    $this->db->select('id_jurnal,id_direktori,title,sari,keywords');
    $this->db->from('jurnal');
    $this->db->where('sari !=',"");
    $this->db->where('sari !=',"null");
    $this->db->where('keywords !=',"");
    $this->db->where('keywords !=',"null");
    $this->db->where('id_direktori !=',"");
    $this->db->where('id_direktori !=',"null");
    return $this->db->get();
  }

  function getDetailArtikel($id){
    $this->db->select('id_jurnal, title');
    $this->db->from('jurnal');
    $this->db->where('id_direktori',$id);
    $this->db->where('sari !=',"");
    $this->db->where('sari !=',"null");
    $this->db->where('keywords !=',"");
    $this->db->where('keywords !=',"null");
    $this->db->where('id_direktori !=',"");
    $this->db->where('id_direktori !=',"null");
    return $this->db->get();

  }

  function insertStemmer($data){
    $this->db->insert('stemmer', $data);
  }

  function getStemmer(){
   return $this->db->get($this->tabel3);

  }
}
?>