<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ ASSET_PATH }}/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="{{ ASSET_PATH }}/custom/fonts.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ ASSET_PATH }}/template/css/sb-admin-2.min.css" rel="stylesheet">

    {{-- for Select 2 --}}
    <link href="{{ ASSET_PATH }}/custom/select2/select2.min.css" rel="stylesheet" />
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">LAKSHMI AGRO</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item {{ request()->routeIs('customer.index') ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('customer.index') }}">
                    <i class="fas fa-solid fa-users"></i>
                    <span>Customer</span>
                </a>
            </li>
            
            <li class="nav-item {{ request()->routeIs('category.index') ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('category.index') }}">
                    <i class="fas fa-solid fa-layer-group"></i>
                    <span>Category</span>
                </a>
            </li>
            
            <li class="nav-item {{ request()->routeIs(['product.index', 'variant.index']) ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('product.index') }}">
                    <i class="fas fa-solid fa-list"></i>
                    <span>Products</span>
                </a>
            </li>
            
            <li class="nav-item {{ request()->routeIs(['invoice.index', 'invoice.create', 'invoice.viewBill']) ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('invoice.index') }}">
                    <i class="fas fa-solid fa-receipt"></i>
                    <span>Billing</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('inventory.index') ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('inventory.index') }}">
                    <i class="fas fa-solid fa-warehouse"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs(['purchase.index', 'purchase.create']) ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('purchase.index') }}">
                    <i class="fas fa-solid fa-store"></i>
                    <span>Purchase</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('user.index') ? 'active' : ''}}">
                <a class="nav-link" href="{{ route('user.index') }}">
                    <i class="fas fa-solid fa-user"></i>
                    <span>User</span>
                </a>
            </li>

            {{-- <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ ASSET_PATH }}/template/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Lakshmi Agro 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();"
                        class="btn btn-primary" href="login.html">Logout</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Bill Modal --}}
    <div class="modal fade" id="editBill" tabindex="-1" role="dialog" aria-labelledby="editBillLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBillLabel">Edit Bill</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editBillForm">
                    @csrf
                    <input type="hidden" name="bill_id" id="bill-edit-id" value="">
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <th>NAME</th>
                                <th>INVOICE</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="bill-edit-name"></td>
                                    <td id="bill-edit-invoice"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label for="" class="form-label">Payment Type</label>
                            <select id="bill-edit-type" name="payment_type" class="form-control">
                                @foreach (PAYMENT_TYPE as $payment)
                                    <option value="{{ $payment }}">{{ $payment }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Amount Paid</label>
                            <input type="text" id="bill-edit-balance" name="balance_amount" class="form-control">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="billupdateBtn" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ ASSET_PATH }}/template/vendor/jquery/jquery.min.js"></script>
    <script src="{{ ASSET_PATH }}/template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ ASSET_PATH }}/template/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ ASSET_PATH }}/template/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="{{ ASSET_PATH }}/template/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ ASSET_PATH }}/template/js/demo/chart-area-demo.js"></script>
    <script src="{{ ASSET_PATH }}/template/js/demo/chart-pie-demo.js"></script>

    <script src="{{ ASSET_PATH }}/custom/datatable/dataTables.js"></script>
    <script src="{{ ASSET_PATH }}/custom/datatable/dataTables.bootstrap4.js"></script>
    {{-- For Sweet message alert --}}
    <script src="{{ ASSET_PATH }}/custom/sweetalert/sweetalert2.js"></script>
    <script src="{{ ASSET_PATH }}/custom/select2/select2.min.js"></script>
    <script>
        select2();
        /* this will convert normal select box to searchable */
        function select2(){
            $('.select2').select2({
                theme: 'classic',
            });
        }

        /* this function get the bill details to edit */
        function editBillDetails(id){
            $.ajax({
                url: '{{ route('invoice.edit') }}',
                type: 'GET',
                data: {
                    'id' : id
                },
                beforeSend: function(){
                    $('#bill-edit-name').empty();
                    $('#bill-edit-invoice').empty();
                    $('#editBillForm').trigger('reset');
                },
                success: function(result){
                    if(result.status == 'success'){
                        $('#bill-edit-name').text(result.invoice.customer.name);
                        $('#bill-edit-invoice').text(result.invoice.invoice_number);
                        $('#bill-edit-id').val(result.invoice.id);
                        $('#bill-edit-balance').val(result.invoice.balance_amount);
                        $('#bill-edit-type').val(result.invoice.payment_mode);
                        $('#editBill').modal('show');
                    }
                    else{
                        showError(result.message);
                    }
                },
                error: function(err){
                    errorMessage(err);
                }
            });
        }

        /* below function to update the bill */
        $(document).on('submit', '#editBillForm', function(event){
            event.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url : '{{ route('invoice.update') }}',
                type : 'POST',
                data : formData,
                beforeSend: function(){
                    $('#billupdateBtn').prop('disabled', true);
                },
                success: function(result){
                    if(result.status == 'success'){
                        successMessage(result.message);
                    }
                    else{
                        showError(result.message);
                    }
                    $('#billupdateBtn').prop('disabled', false);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error: function(err){
                    errorMessage(err);
                    $('#billupdateBtn').prop('disabled', false);

                }
            });

        });

        /* below function to delete the bill */
        function deleteBill(id){
            let dataId = id;
            let deleteUrl = "{{ route('invoice.delete', ':id') }}";
            let url = deleteUrl.replace(':id', dataId);
            
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(result){
                            if(result.status == 'success'){
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your data has been deleted.",
                                    icon: "success"
                                });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                            else{
                                Swal.fire({
                                    title: "Not Deleted!",
                                    text: "Your data hasn't been deleted.",
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        }
        
        /* for show error message */
        function errorMessage(err){
            let errorMessage = 
                Object.entries(err.responseJSON.errors)
                .map(([field, message]) => `${message.join('<br>')}`)
                .join('<br>');
        
            Swal.fire({
                icon: "error",
                title: "Oops...",
                html: errorMessage,
            });
        }

        /* for show success message */
        function successMessage(message){
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: message,
                showConfirmButton: false,
                timer: 1500
            });
        }

        /* for show error message */
        function showError(err){
            Swal.fire({
                icon: "error",
                title: "Error",
                html: err,
            });
        }
    </script>
    @yield('scripts')
</body>

</html>