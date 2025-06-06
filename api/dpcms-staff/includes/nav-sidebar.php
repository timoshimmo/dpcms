		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class='sidebar-brand' href='index.php'>
					<span class="sidebar-brand-text align-middle">
						<img src="https://i.ibb.co/prWn33kB/destiny-pdf.jpg" style="max-width: 100%;">
						
					</span>
					<!-- <svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5"
						stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF" style="margin-left: -3px">
						<path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
						<path d="M20 12L12 16L4 12"></path>
						<path d="M20 16L12 20L4 16"></path>
					</svg> -->
				</a>

				<div class="sidebar-user">
					<div class="d-flex justify-content-center">
						<div class="flex-shrink-0">
							<img src="img/photos/profile-icon-png-909.png" class="avatar img-fluid rounded me-1" alt="<?php echo ucwords($admin_username); ?>" />
						</div>
						<div class="flex-grow-1 ps-2">
							<a class="sidebar-user-title dropdown-toggle" href="#" data-bs-toggle="dropdown">
								<?php echo ucwords($admin_username); ?>
							</a>
<!-- 							<div class="dropdown-menu dropdown-menu-start">
								<a class='dropdown-item' href='pages-profile'><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class='dropdown-item' href='pages-settings'><i class="align-middle me-1" data-feather="settings"></i> Settings &
									Privacy</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Log out</a>
							</div> -->

							<div class="sidebar-user-subtitle"><?php echo ucwords(str_replace("_", " ", $admin_role)); ?></div>
						</div>
					</div>
				</div>

				<ul class="sidebar-nav">
					<li class="sidebar-item active">
						<a class='sidebar-link' href='overview'>
							<i class="align-middle" data-feather="home"></i> <span class="align-middle">Overview</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a data-bs-target="#administrators" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Administrator</span>
						</a>
						<ul id="administrators" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='manage-administrators'>Manage Administrators</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='my-login-history'>My Login History</a></li>
							
						</ul>
					</li>

					<li class="sidebar-item" style="display: none;">
						<a data-bs-target="#complaints" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="layout"></i> <span class="align-middle">Complaints</span>
						</a>
						<ul id="complaints" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Pending Complaints</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Ongoing Complaints</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Completed Complaints</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Flagged Complaints</a></li>

							
						</ul>
					</li>

					<li class="sidebar-item" style="display: none;">
						<a class='sidebar-link' href='#'>
							<i class="align-middle" data-feather="lock"></i> <span class="align-middle">Change Password</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a data-bs-target="#auth" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Members</span>
						</a>
						<ul id="auth" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='manage-active-members'>Manage All Active Members</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='manage-in-active-members'>Manage Inactive Members</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='general-members-default-records'>Manage Members Defaults</a></li>

							
						</ul>
					</li>


					<li class="sidebar-item" style="display: none;">
						<a data-bs-target="#dpaymentprof" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="info"></i> <span class="align-middle">All Payment Proof</span>
						</a>
						<ul id="dpaymentprof" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='payment-proof-reg'>Reg Payment Proof</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='wallet-funding-proof'>Manual Funding Proof</a></li>
							
						</ul>
					</li>

					


		    		<li class="sidebar-item">
						<a data-bs-target="#more-freebies" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="activity"></i> <span class="align-middle">General Payment Issue</span>
						</a>
						<ul id="more-freebies" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='virtual-session-id-validation'>Virtual Session ID Validation</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='javascript:void();'>Flutterwave Online Payment (Card/USSD) Validation</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='check-details-with-virtual-account'>Check Profile with Virtual Account Number</a></li>
						</ul>
					</li>

					<li class="sidebar-item">
						<a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="list"></i> <span class="align-middle">Settlement Accounts</span>
						</a>
						<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Paid Settlements Account</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Pending Withdrawal Settlements Account</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='manage-waybill-only-settlement-account'>Waybill-Only Pending Settlements Account</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='manage-waybill-account-cleared-from-dashboard'>Waybill Account Cleared from Dashboard-Only</a></li>


							
						</ul>
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#pages" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Transactions</span>
						</a>
						<ul id="pages" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='admin-manage-wallet-transactions'>Wallet Transactions</a></li>
							<!-- <li class="sidebar-item"><a class='sidebar-link' href='admin-manage-virtual-transactions'>Virtual Transactions </a></li> -->
							<li class="sidebar-item"><a class='sidebar-link' href='thrift-transactions'>Thrift Transactions </a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='admin-manage-virtual-transactions'>Virtual Transactions </a></li>

							<!--<li class="sidebar-item"><a class='sidebar-link' href='payment-proof-reg'>Payment Proof </a></li>-->
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Fast Track History </a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Request Withdrawal History </a></li>
						</ul>
					</li>


					<li class="sidebar-item" style="display:none;">
						<a data-bs-target="#more-freebies" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="activity"></i> <span class="align-middle">More Stuffs</span>
						</a>
						<ul id="more-freebies" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Customers Testimonies</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Staffs Ranking</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>Submit Weekly Reports</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='#'>My Attendance</a></li>

							
						</ul>
					</li>

					<li class="sidebar-item">
						<a class='sidebar-link' href='log-out'> 
							<i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Log Out</span>
						</a>
					</li>

<!-- 					<li class="sidebar-header">
						Pages
					</li>
					<li class="sidebar-item active">
						<a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link">
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboards</span>
						</a>
						<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse show" data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='index.html'>Analytics</a></li>
							<li class="sidebar-item active"><a class='sidebar-link' href='dashboard-ecommerce'>E-Commerce <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='dashboard-crypto'>Crypto <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>

					<li class="sidebar-item">
						<a data-bs-target="#pages" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="layout"></i> <span class="align-middle">Pages</span>
						</a>
						<ul id="pages" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='pages-settings'>Settings</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-projects'>Projects <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-clients'>Clients <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-orders'>Orders <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-pricing'>Pricing <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-chat'>Chat <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-blank'>Blank Page</a></li>
						</ul>
					</li>

					<li class="sidebar-item">
						<a class='sidebar-link' href='pages-profile'>
							<i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class='sidebar-link' href='pages-invoice'>
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Invoice</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class='sidebar-link' href='pages-tasks'>
							<i class="align-middle" data-feather="list"></i> <span class="align-middle">Tasks</span>
							<span class="sidebar-badge badge bg-primary">Pro</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class='sidebar-link' href='calendar'>
							<i class="align-middle" data-feather="calendar"></i> <span class="align-middle">Calendar</span>
							<span class="sidebar-badge badge bg-primary">Pro</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a href="#auth" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="users"></i> <span class="align-middle">Auth</span>
						</a>
						<ul id="auth" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='pages-sign-in'>Sign In</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-sign-up'>Sign Up</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-reset-password'>Reset Password <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-404'>404 Page <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-500'>500 Page <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>

					<li class="sidebar-header">
						Components
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#ui" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">UI Elements</span>
						</a>
						<ul id="ui" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='ui-alerts'>Alerts <span
										class="sidebar-badge badge bg-primary">Pro</span></a></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-buttons'>Buttons</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-cards'>Cards</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-general'>General</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-grid'>Grid</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-modals'>Modals <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-offcanvas'>Offcanvas <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-placeholders'>Placeholders <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-tabs'>Tabs <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='ui-typography'>Typography</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#icons" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="coffee"></i> <span class="align-middle">Icons</span>
							<span class="sidebar-badge badge bg-light">1.500+</span>
						</a>
						<ul id="icons" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='icons-feather'>Feather</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='icons-font-awesome'>Font Awesome <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#forms" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="check-circle"></i> <span class="align-middle">Forms</span>
						</a>
						<ul id="forms" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='forms-basic-inputs'>Basic Inputs</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='forms-layouts'>Form Layouts <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='forms-input-groups'>Input Groups <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a class='sidebar-link' href='tables-bootstrap'>
							<i class="align-middle" data-feather="list"></i> <span class="align-middle">Tables</span>
						</a>
					</li>

					<li class="sidebar-header">
						Plugins & Addons
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#form-plugins" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="check-square"></i> <span class="align-middle">Form Plugins</span>
						</a>
						<ul id="form-plugins" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='forms-advanced-inputs'>Advanced Inputs <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='forms-editors'>Editors <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='forms-validation'>Validation <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#datatables" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="list"></i> <span class="align-middle">DataTables</span>
						</a>
						<ul id="datatables" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='tables-datatables-responsive'>Responsive Table <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='tables-datatables-buttons'>Table with Buttons <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='tables-datatables-column-search'>Column Search <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='tables-datatables-fixed-header'>Fixed Header <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='tables-datatables-multi'>Multi Selection <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='tables-datatables-ajax'>Ajax Sourced Data <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#charts" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Charts</span>
						</a>
						<ul id="charts" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='charts-chartjs'>Chart.js</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='charts-apexcharts'>ApexCharts <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a class='sidebar-link' href='notifications'>
							<i class="align-middle" data-feather="bell"></i> <span class="align-middle">Notifications</span>
							<span class="sidebar-badge badge bg-primary">Pro</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a data-bs-target="#maps" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="map"></i> <span class="align-middle">Maps</span>
						</a>
						<ul id="maps" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='maps-google'>Google Maps</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='maps-vector'>Vector Maps <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
						</ul>
					</li>

					<li class="sidebar-item">
						<a data-bs-target="#multi" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="corner-right-down"></i> <span class="align-middle">Multi Level</span>
						</a>
						<ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
							<li class="sidebar-item">
								<a data-bs-target="#multi-2" data-bs-toggle="collapse" class="sidebar-link collapsed">Two Levels</a>
								<ul id="multi-2" class="sidebar-dropdown list-unstyled collapse">
									<li class="sidebar-item">
										<a class="sidebar-link" href="#">Item 1</a>
									</li>
									<li class="sidebar-item">
										<a class="sidebar-link" href="#">Item 2</a>
									</li>
								</ul>
							</li>
							<li class="sidebar-item">
								<a data-bs-target="#multi-3" data-bs-toggle="collapse" class="sidebar-link collapsed">Three Levels</a>
								<ul id="multi-3" class="sidebar-dropdown list-unstyled collapse">
									<li class="sidebar-item">
										<a data-bs-target="#multi-3-1" data-bs-toggle="collapse" class="sidebar-link collapsed">Item 1</a>
										<ul id="multi-3-1" class="sidebar-dropdown list-unstyled collapse">
											<li class="sidebar-item">
												<a class="sidebar-link" href="#">Item 1</a>
											</li>
											<li class="sidebar-item">
												<a class="sidebar-link" href="#">Item 2</a>
											</li>
										</ul>
									</li>
									<li class="sidebar-item">
										<a class="sidebar-link" href="#">Item 2</a>
									</li>
								</ul>
							</li>
						</ul>
					</li> -->
				</ul>

<!-- 				<div class="sidebar-cta" style="display: none;">
					<div class="sidebar-cta-content">
						<strong class="d-inline-block mb-2">Weekly Sales Report</strong>
						<div class="mb-3 text-sm">
							Your weekly sales report is ready for download!
						</div>

						<div class="d-grid">
							<a href="https://adminkit.io/" class="btn btn-outline-primary" target="_blank">Download</a>
						</div>
					</div>
				</div> -->
			</div>
		</nav>
		<!-- end nav bar by kz -->