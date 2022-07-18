<?php // if (login_check($mysqli) == true) : ?>
       <!-- content ends here -->
            <?php if($tpPag==1){?>        
            		 </div>
                </div>
            </div>
        </div> 
    </div>
</div><?php } ?>  

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            ERP-Sinc by <a target="_blank" href="https://pombaldir.com">Pombaldir.com</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo URLBASE;?>/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo URLBASE;?>/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo URLBASE;?>/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo URLBASE;?>/vendors/nprogress/nprogress.js"></script>
    <!-- jQuery custom content scroller -->
    <script src="<?php echo URLBASE;?>/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    
	<!-- PNotify -->
    <script src="<?php echo URLBASE;?>/vendors/pnotify/dist/pnotify.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/pnotify/dist/pnotify.nonblock.js"></script>
    
	<?php
	if (file_exists(DOCROOT."/include/$p.footer.inc.php")) {	
    include(DOCROOT."/include/$p.footer.inc.php"); 
	}
	?>

    <!-- Custom Theme Scripts -->
    <script src="<?php echo URLBASE;?>/build/js/custom.min.js"></script>
    
  </body>
</html><?php // endif; ?>