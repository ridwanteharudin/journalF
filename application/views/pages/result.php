<div class="container">
    <table id="example" class="display" style="width:100%">
      <thead>
        <tr>
          <th scope="col">Match</th>
          <th scope="col">Id Direktori</th>
          <th scope="col">Judul</th>
          <th scope="col">Penerbit</th>
          <th scope="col">Alamat</th>
          <th scope="col">Editor</th>
          <th scope="col">Deskriptor</th>
          <th scope="col">Artikel Terkait</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $key=>$list) { ?>
            <tr>
              <td><?php echo $list['match'];?></td>
              <td><?php echo $key;?></td>
              <td><?php echo $list['judul'];?></td>
              <td><?php echo $list['penerbit'];?></td>
              <td><?php echo $list['alamat'];?></td>
              <td><?php echo $list['editor'];?></td>
              <td><?php echo $list['deskriptor'];?></td>
              <td><?php echo $list['action'];?></td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
     
</div>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        "order": [[ 0, "desc" ]]
      });
} );

function detail_jurnal(id){
  $("#"+id).modal();
  /*$.ajax({
        url : "<?php //echo site_url('Welcome/detail_artikel/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(jurnal)
        {
 
            $('[name="id"]').val(jurnal.id_jurnal);
            $('[name="judul"]').val(jurnal.title);
            $('[name="alamat"]').val(jurnal.alamat);
            $('[name="deskriptor"]').val(jurnal.deskriptor);
            $('[name="editor"]').val(jurnal.editor);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Detail Jurnal'); // Set title to Bootstrap modal title
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });*/
   
}
</script>

<?php foreach ($data as $key=>$list) { ?>
  <div class="modal fade" id="<?php echo $key;?>" role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title">Detail Artikel</h3>
              </div>
              <div class="modal-body form">
                  <form action="#" id="form" class="form-horizontal">
                      <input type="hidden" value="" name="id"/> 
                      <div class="form-body">
                          <div class="form-group">
                              <label class="control-label col-md-3">Artikel Terkait</label>
                              <div class="col-md-9">
                                  <label>
                                    <?php
                                      $iterasi = 1; 
                                      foreach ($list['totalstep'] as $key) {
                                        echo $iterasi.". ".$key."<br>"."<br>";
                                        $iterasi++;
                                  };?></label>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="control-label col-md-3">Deskriptor</label>
                              <div class="col-md-9">
                                  <input name="deskriptor" placeholder="Last Name" class="form-control" type="text">
                                  <span class="help-block"></span>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
  <?php } ?>