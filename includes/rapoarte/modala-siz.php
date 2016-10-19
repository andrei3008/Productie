<?php
    /*------------------------------------------------
     *  MODALA SIZ SI SIL
     *----------------------------------------------*/
    // error_reporting(E_ALL);
    include_once 'config_rapoarte.php';

?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="modal-raport">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="raport-modal-title">Generare rapoarte SITUATIE INCASARI ZILNICE <span id="nume_firma"></span></h4>
        <h5 id="nume_locatie"></h5>
      </div>
      <div class="modal-body" id="cont-siz">
          <div class="container-fluid">
              <div class="filter">
                <div class="row">
                  
                  <div class="col-md-6">
                      <label>Selectati anul</label><br />
                      <select type="text" id="input-an" name="input-an" class="form-control  fullw">
                        <option value="2016">2016</option>
                      </select>
                  </div>
                <div class="col-md-6">
                      <label>Selectati Luna</label><br />
                      <select type="text" id="input-luna" name="input-luna" class="form-control fullw">
                        <?php
                          for ($i = 1; $i <= 12; $i++) {
                            $inc = (strlen($i) == 1) ? '0'.$i : $i;
                            $luna = $data_rom->luna_ro($i);
                            $selected = ($inc == date('m')) ? 'selected="selected"': '';
                            echo '<option value="'.$inc.'" '.$selected.'>'.$luna.'</option>';
                          }
                        ?>
                      </select>
                  </div>
                </div>

                <div class="row" id="">
                  <div class="col-md-12"><div id="zile-luna"></div></div>
                </div>
              </div>
              <div id="container-raport">
                  <Br />
                    <div class="btn-group btn-print-group-luna">
                          <button type="button" class="btn btn-primary btn-sm dropdown-toggle btn-print-current" 
                              data-perioada="lunar" 
                              data-toggle="dropdown" 
                              aria-haspopup="true" aria-expanded="false">
                              Printeaza luna selectata
                              <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                              <li><a href="#" data-ext="pdf">PDF</a></li>
                              <li><a href="#" data-ext="xls">.xls</a></li>
                          </ul>
                    </div><br />
                <div class="row" id="">
                  <div class="col-md-12"><div id="raport2"></div></div>
                </div>
              </div>
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" name="raport-tip" id="raport-tip" value="" />
<input type="hidden" name="raport-firma" id="raport-firma" value="" />
<input type="hidden" name="raport-locatie" id="raport-locatie" value="" />
<input type="hidden" name="raport-operator" id="raport-operator" value="<?php echo $_SESSION['operator'];?>" />
<iframe id="raport-download" style="display:none;"></iframe>
<div id="loading"><img src="includes/rapoarte/loader.gif" /></div>