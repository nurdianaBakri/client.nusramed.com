 
                        <form id="myForm" method="post" action="<?= base_url()."Outlet/save" ?>"> 
                            <h3>Data Outlet</h3>
                        
                          <?php  $id = $this->session->userdata('id');  ?>  
                          <input type="hidden" name="USER_INPUT" value="<?php echo $id ?>" > 
                          <input type="hidden" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" > 
                          <input type="hidden" name="id_outlet" value="<?php echo $id_outlet; ?>" > 
                          <input type="hidden" name="id_penanggung_jawab" value="<?php echo $penanggung_jawab['id_penanggung_jawab']; ?>" > 

                          <div class="row">
                            <div class="col-md-2">
                                <label for="nama">Nama</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="nama" required name="nama" class="form-control input-visible" value="<?php echo $outlet['nama'] ?>"> 
                            </div>
                        </div>

                          <div class="row">
                            <div class="col-md-2">
                                <label for="alamat">Alamat</label>
                            </div>
                            <div class="col-md-10">
                                 <textarea required name="alamat" class="form-control input-visible">
                                    <?php echo $outlet['alamat'] ?>
                                </textarea>  
                            </div>
                        </div>

                          <div class="row">
                            <div class="col-md-2">
                                <label for="npwp">NPWP</label>
                            </div>
                            <div class="col-md-4">
                              <input type="number" id="npwp" required name="npwp" class="form-control input-visible" value="<?php echo $outlet['npwp'] ?>">
                            </div>
                             <div class="col-md-2">
                                <label for="no_telp">No. Telp</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" id="no_telp" name="no_telp" class="form-control input-visible" value="<?php echo $outlet['no_telp'] ?>"> 
                            </div>
                        </div>  
                        <hr>   

                        <h3>Data Pemilik</h3> 
                         <div class="row"> 
                           <div class="col-md-2">
                                <label for="no_ktp_pemilik">NIK</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" id="no_ktp_pemilik" required name="no_ktp_pemilik" class="form-control input-visible" value="<?php echo $outlet['no_ktp_pemilik'] ?>"> 
                            </div>
                             <div class="col-md-2">
                                <label for="nm_pemilik">Nama</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="nm_pemilik" name="nm_pemilik" class="form-control input-visible" value="<?php echo $outlet['nm_pemilik'] ?>"> 
                            </div>
                        </div>  
                         
                        <div class="row">
                            <div class="col-md-2">
                                <label for="alamat_pemilik">Alamat</label>
                            </div>
                            <div class="col-md-10"> 
                                <textarea required name="alamat_pemilik" class="form-control input-visible">
                                    <?php echo $outlet['alamat_pemilik'] ?>
                                </textarea> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label for="no_telp_pemilik">No.Telp</label>
                            </div>
                            <div class="col-md-10">
                              <input type="number" id="no_telp_pemilik" name="no_telp_pemilik" class="form-control input-visible" value="<?php echo $outlet['no_telp_pemilik'] ?>">
                            </div>
                        </div>   
                        <hr>  

                        <h3>Data Penaggung Jawab</h3>  
                        <div class="row">
                            <div class="col-md-2">
                                <label for="no_ktp_pj">NIK</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" id="no_ktp_pj" required name="no_ktp_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_ktp_pj'] ?>"> 
                            </div>
                             <div class="col-md-2">
                                <label for="email_pj">Email</label>
                            </div>
                            <div class="col-md-4">
                                <input type="email" id="email_pj" required name="email_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['email_pj'] ?>"> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label for="nm_pj">Nama Penaggung Jawab</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="nm_pj" required name="nm_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['nama_pj'] ?>"> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label for="alamat_pj">Alamat</label>
                            </div>
                            <div class="col-md-10">
                                <textarea required name="alamat_pj" class="form-control input-visible"><?php echo $penanggung_jawab['alamat_pj'] ?></textarea> 
                            </div>
                        </div>  

                        <div class="row">
                            <div class="col-md-2">
                                <label for="no_str">No. STR</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="no_str" required name="no_str" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_str_pj'] ?>"> 
                            </div>
                             <div class="col-md-2">
                                <label for="no_sipa">No. SIPA</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="no_sipa" required name="no_sipa" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_sipa_pj'] ?>"> 
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-md-2">
                                <label for="no_sia">No. SIA</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="no_sia" required name="no_sia" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_sia_pj'] ?>"> 
                            </div>
                            <div class="col-md-2">
                                <label for="no_ijin_rs">No. Ijin RS</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="no_ijin_rs" required name="no_ijin_rs" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_ijin_rs_pj'] ?>"> 
                            </div>
                        </div>
 
                         <div class="row">
                            <div class="col-md-2">
                                <label for="no_ijin_klinik">No. Ijin Klinik</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="no_ijin_klinik" required name="no_ijin_klinik" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_ijin_klinik_pj'] ?>"> 
                            </div>
                             <div class="col-md-2">
                                <label for="no_telp_pj">No.Telp</label>
                            </div>
                            <div class="col-md-4">
                              <input id="no_telp_pj" type="number" required name="no_telp_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_telp_pj'] ?>">
                            </div>
                        </div>    

                        <input class="btn btn-success" type="submit" name="submit" value="Simpan">

                    </form>  