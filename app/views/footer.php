    <!-- ADDED JS IN FOOTER -->
    <?php
        if(isset($footer_js) and is_array($footer_js)){
			foreach($footer_js as $jsLink){
				echo '<script type="text/javascript" src="' . $jsLink . '"></script>' . "\n";
			}
		}
	?>
</body>

</html>