 
                        <form id="myForm" method="post" action="<?= base_url()."user/save" ?>" class="form-horizontal">   
                        
                          <?php  $id = $this->session->userdata('id');  ?>  
                          <input type="hidden" name="USER_INPUT" value="<?php echo $id ?>" > 
                          <input type="hidden" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" >  
                          <input type="hidden" name="id" value="<?php echo $data['id'] ?>" >  

                          <div class="form-group">
                           <label  class="col-md-2" for="username">Username</label>
                            <div class="col-md-4">
                                <input type="text" id="username" required name="username" class="form-control input-visible" value="<?php echo $data['username'] ?>"> 
                            </div>

                            <label  class="col-md-2" for="password">Password</label>
                            <div class="col-md-4">
                                <input type="password" id="password" <?php if($jenis_aksi=="add"){ echo "required"; }  ?>  name="password" class="form-control input-visible" value=""> 
                            </div>
                        </div>  

                          <div class="form-group">

                            <label  class="col-md-2" for="nama">Nama</label>
                            <div class="col-md-4">
                                <input type="text" id="nama" required name="nama" class="form-control input-visible" value="<?php echo $data['nama'] ?>"> 
                            </div>

                           <label  class="col-md-2" for="email">Email</label>
                            <div class="col-md-4">
                                <input type="email" id="email" required name="email" class="form-control input-visible" value="<?php echo $data['email'] ?>"> 
                            </div>
                        </div>    

                         <div class="form-group">
                           <label  class="col-md-2" for="no_hp">No HP</label>
                            <div class="col-md-4">
                                <input type="number" id="no_hp" required name="no_hp" class="form-control input-visible" value="<?php echo $data['no_hp'] ?>"> 
                            </div>

                            <label  class="col-md-2" for="no_hp">Status Aktif</label>
                            <div class="col-md-4">
                                <select class="form-control" name="deleted">
                                    <option value="1" <?php if($data['deleted']){ echo "selected"; } ?>>Tidak Aktif</option>
                                    <option value="0" <?php if($data['deleted']){ echo "selected"; } ?>>Aktif</option>
                                </select>
                            </div>
                        </div>

                          <div class="form-group">

                            <label  class="col-md-2" for="no_hp">Jabatan</label>
                            <div class="col-md-4">
                                <select class="form-control" name="jabatan">
                                    <option value="super_admin" <?php if($data['jabatan']=="super_admin"){ echo "selected"; } ?>>Super Admin</option>
                                    <option value="admin" <?php if($data['jabatan']=="admin"){ echo "selected"; } ?>>Admin</option>
                                    <option value="apoteker" <?php if($data['jabatan']=="apoteker"){ echo "selected"; } ?>>Apoteker</option>
                                    <option value="asisten_apoteker" <?php if($data['jabatan']=="asisten_apoteker"){ echo "selected"; } ?>>Asisten Apoteker</option>
                                    <option value="it" <?php if($data['jabatan']=="it"){ echo "selected"; } ?>>it</option>
                                    <option value="akutan" <?php if($data['jabatan']=="akutan"){ echo "selected"; } ?>>Akutan</option>
                                </select>
                            </div>

                           <label  class="col-md-2" for="alamat">Alamat</label>
                            <div class="col-md-4">
                                 <textarea required name="alamat" class="form-control input-visible">
                                    <?php echo $data['alamat'] ?>
                                </textarea>  
                            </div>
                        </div> 


                        <input class="btn btn-success" type="submit" name="submit" value="Simpan">

                    </form>  