<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <span class="glyphicon glyphicon-file">
                                </span>Journal Finder</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                    	<form method="post" action="<?php echo base_url(); ?>Welcome/input_data">
                            <br>
                            <div id="semua" class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="judul" class="form-group">
                                            <input name="judul" type="text" class="form-control" placeholder="Title"/>
                                        </div>
                                        <div id="abstrak" class="form-group">
                                            <textarea placeholder="abstrak" name="abstrak" class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="kata_kunci" class="form-group">
                                            <input name="keyword" type="text" class="form-control" id="tags" placeholder="Keyword" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="checkbox" name="bidang[]" value="teknologi">Teknologi
                                        <input type="checkbox" name="bidang[]" value="ekonomi">Ekonomi<br>
                                        <input type="checkbox" name="bidang[]" value="bisnis">Bisnis
                                        <input type="checkbox" name="bidang[]" value="biologi">Biologi<br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="well well-sm well-primary">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <span class="glyphicon glyphicon-floppy-disk">
                                                    </span>Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
