<div class="wrap">
<h2>BenchMarkly Settings</h2>
<div class="form">
<?php if( !@$benchmarks_available ) : ?>
	<p><?php _e("Welcome to your benchmark settings! If this is your first time we first need to analyze your server environment and see which benchmarks are available to you." , "benchmarkly" ); ?></p>
	<?php submit_button("Check for Benchmarks","action-btn-click","check_benchmarks",NULL,array("data-method"=>"get")); ?>

	<div id="bmToggle" class="side-sortables collapsible accordion-container">
		<div class="outer-border">
			<div class="control-section accordion-section">
				<h3 class="accordion-section-title hndle"><?php _e("Benchmarks","benchmarkly"); ?></h3>
				<div class="accordion-section-content">
					<div class="inside">
						<div class="form-control clear clearfix"><div style="text-align:right;">Toggle Benchmark: 
							<select class="select" name="benchmark" >
								<?php foreach( $benchmarks->benchmarks as $benchmark ) : ?>
									<option value="<?php echo $benchmark->key; ?>"><?php echo $benchmark->label; ?></option>
								<?php endforeach; ?>
							</select>
							</div>
						</div>
						<input type="hidden" name="current-benchmark" id="current-benchmark" value="<?php echo $default_benchmark; ?>" />
						<p class=""><div id="mainChart" class="demo-container demo-placeholder"></div></p>
					</div>
				</div>
				<h3 class="accordion-section-title hndle"><?php _e("Benchmarks","benchmarkly"); ?></h3>
				<div class="accordion-section-content">
					<div class="inside">
						<p class="menu-item-name-wrap">test</p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>



</div>
</div>
