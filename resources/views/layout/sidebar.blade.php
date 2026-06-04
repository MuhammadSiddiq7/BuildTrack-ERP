<nav id="sidebar" class="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-user">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/logo/adcc.png') }}" class="img-fluid mb-2"
                    alt="Anchor Development and Construction Company" />
            </a>
        </div>
        <ul class="sidebar-nav">
            @php
                $isDashboardActive = Route::is('dashboard');
            @endphp

            <li class="sidebar-item {{ $isDashboardActive ? 'active' : '' }}">
                <a data-bs-target="#dashboards" data-bs-toggle="collapse"
                    class="sidebar-link {{ $isDashboardActive ? '' : 'collapsed' }}">
                    <i class="align-middle me-2 fas fa-fw fa-home"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
                <ul id="dashboards"
                    class="sidebar-dropdown list-unstyled collapse {{ $isDashboardActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a class='sidebar-link {{ Route::is('dashboard') ? 'active' : '' }}'
                            href='{{ route('dashboard') }}'>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </li>

            {{-- @canany(['role_view', 'user_view', 'user_activity_view']) --}}
            @php
                $isUserManagementActive =
                    request()->routeIs('role.*') ||
                    request()->routeIs('user.*') ||
                    request()->routeIs('user_activity.*');
            @endphp
            <li class="sidebar-item {{ $isUserManagementActive ? 'active' : '' }}">
                <a data-bs-target="#user_management" data-bs-toggle="collapse"
                    class="sidebar-link {{ $isUserManagementActive ? '' : 'collapsed' }}">
                    <i class="align-middle me-2 fas fa-fw fa-user"></i>
                    <span class="align-middle">User Management</span>
                </a>
                <ul id="user_management" class="sidebar-dropdown list-unstyled
                    collapse"
                    {{ $isUserManagementActive ? 'show' : '' }} data-bs-parent="#sidebar">
                    @can('role_view')
                        <li class="sidebar-item {{ request()->routeIs('role.*') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('role.index') }}'>Roles</a>
                        </li>
                    @endcan
                    @can('user_view')
                        <li class="sidebar-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <a class='sidebar-link {{ Request::is('user.index') ? 'active' : '' }}'
                                href='{{ route('user.index') }}'>Users</a>
                        </li>
                    @endcan
                    @can('user_activity_view')
                        <li class="sidebar-item {{ request()->routeIs('user_activity.*') ? 'active' : '' }}">
                            <a class='sidebar-link {{ Request::is('user_activity.index') ? 'active' : '' }}'
                                href='{{ route('user_activity.index') }}'>User Activity</a>
                        </li>
                    @endcan
                    @can('all_notifications_view')
                        <li class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <a class='sidebar-link {{ Request::is('notifications.index') ? 'active' : '' }}'
                                href='{{ route('notifications.index') }}'>All Notifications</a>
                        </li>
                    @endcan

                </ul>
            </li>
            @php
                $isActivityActive =
                 Route::is('activity*') ||
                 Route::is('project*') ||
                 Route::is('contractor*') ||
                 Route::is('plan*') ||
                 Route::is('billing.index');
            @endphp
            <li class="sidebar-item {{ $isActivityActive ? 'active' : '' }}">
                <a data-bs-target="#monitoring" data-bs-toggle="collapse"
                    class="sidebar-link {{ $isActivityActive ? '' : 'collapsed' }}">
                    <i class="fa-solid fa-desktop"></i>
                    <span class="align-middle">Monitoring</span>
                </a>
                <ul id="monitoring" class="sidebar-dropdown list-unstyled collapse {{ $isActivityActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar">
                    @can('project_view')
                        <li class="sidebar-item {{ request()->routeIs('project.*') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('project.index') }}'>Projects</a>
                        </li>
                    @endcan
                    @can('contractor_view')
                        <li class="sidebar-item {{ request()->routeIs('contractor.*') ? 'active' : '' }} ">
                            <a class='sidebar-link' href='{{ route('contractor.index') }}'>Contractors</a>
                        </li>
                    @endcan
                    @can('activity_view')
                        <li class="sidebar-item {{ Route::is('activity.index') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('activity.index') }}'>Activity</a>
                        </li>
                    @endcan
                    @can('planning_view')
                        <li class="sidebar-item {{ Route::is('plan.index') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('plan.index') }}'>Planning</a>
                        </li>
                    @endcan
                    @can('billing_view')
                        <li class="sidebar-item {{ Route::is('billing.index') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('billing.index') }}'>Billing</a>
                        </li>
                    @endcan
                    @can('holiday_view')
                        <li class="sidebar-item {{ Route::is('holiday.index') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('holiday.index') }}'>Holiday</a>
                        </li>
                    @endcan
                    @can('holiday_view')
                      <li class="sidebar-item {{ Route::is('contractor.progress.show') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('contractor.progress.show') }}">Contractor Progress</a>
                    </li>
                    @endcan
                </ul>
            </li>
            {{-- @endcanany
            @canany(['item_view']) --}}
            @php
                $isItemActive = Route::is('item.*') || Route::is('item.index.b.*');
            @endphp
            <li class="sidebar-item {{ $isItemActive ? 'active' : '' }}">
                <a data-bs-target="#item" data-bs-toggle="collapse"
                    class="sidebar-link {{ $isItemActive ? '' : 'collapsed' }}">
                    <i class="align-middle me-2 fas fa-clone"></i>
                    <span class="align-middle">Items Category</span>
                </a>
                <ul id="item" class="sidebar-dropdown list-unstyled collapse {{ $isItemActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar">
                    @can('item_view')
                        <li class="sidebar-item {{ request()->routeIs('item.*') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('item.index') }}'>A Type Houses</a>
                        </li>
                    @endcan
                    @can('item_b_view')
                        <li class="sidebar-item {{ Route::is('item.index.b') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('item.index.b') }}'>B Type Houses</a>
                        </li>
                    @endcan
                    @can('item_c_view')
                        <li class="sidebar-item {{ Route::is('item.index.c') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('item.index.c') }}'>C Type Houses</a>
                        </li>
                    @endcan
                    @can('item_d_view')
                        <li class="sidebar-item {{ Route::is('item.index.d') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('item.index.d') }}'>D Type Houses</a>
                        </li>
                    @endcan
                </ul>
            </li>
            {{-- @endcanany
            @canany(['project_view', 'supplier_view', 'contractor_view']) --}}
            @php
                $isInventoryActive =
                    request()->routeIs('itemDemand.*') ||
                    request()->routeIs('supplier.*') ||
                    request()->routeIs('contractorProject.*') ||
                    // request()->routeIs('houseType.*') ||
                    request()->routeIs('purchaseOrder.*') ||
                    request()->routeIs('warehouse.*');
            @endphp
            <li class="sidebar-item {{ $isInventoryActive ? 'active' : '' }}">
                <a data-bs-target="#inventory" data-bs-toggle="collapse"
                    class="sidebar-link {{ $isInventoryActive ? '' : 'collapsed' }}">
                    <i class="align-middle me-2 fas fa-fw fa-users"></i> <span class="align-middle">Procurement</span>
                </a>
                <ul id="inventory" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    @can('itemDemand_view')
                        <li class="sidebar-item {{ request()->routeIs('itemDemand.*') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('itemDemand.index') }}'>Item Demand</a>
                        </li>
                    @endcan

                    @can('quotation_view')
                        <li class="sidebar-item {{ request()->routeIs('quotation.*') ? 'active' : '' }} ">
                            <a class='sidebar-link' href='{{ route('quotation.index') }}'>Quotation</a>
                        </li>
                    @endcan
                    @can('comparativeStatement_view')
                        <li class="sidebar-item {{ request()->routeIs('comparativeStatement.*') ? 'active' : '' }} ">
                            <a class='sidebar-link' href='{{ route('comparativeStatement.index') }}'>Comparative
                                Statement</a>
                        </li>
                    @endcan
                    @can('supplier_view')
                        <li class="sidebar-item {{ request()->routeIs('supplier.*') ? 'active' : '' }} ">
                            <a class='sidebar-link' href='{{ route('supplier.index') }}'>Suppliers</a>
                        </li>
                    @endcan

                    {{-- @can('contractorProject_view')
                    <li class="sidebar-item {{ request()->routeIs('contractorProject.*') ? 'active' : '' }} ">
                        <a class='sidebar-link' href='{{ route('contractor.project.index') }}'>Contractor Projects</a>
                    </li>
                    @endcan
                    @can('houseType_view')
                        <li class="sidebar-item {{ request()->routeIs('houseType.*') ? 'active' : '' }} ">
                            <a class='sidebar-link' href='{{ route('houseType.index') }}'>House Type</a>
                        </li>
                    @endcan --}}
                    @can('warehouse_view')
                        <li class="sidebar-item {{ request()->routeIs('warehouse.*') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('warehouse.index') }}'>Warehouses</a>
                        </li>
                    @endcan

                </ul>
            </li>
            {{-- Po --}}
            @php
                $isPurchaseOrderActive =
                    Route::is('purchaseOrder.*') || Route::is('po.*') || Route::is('marge.po.invoice');
            @endphp
                <li class="sidebar-item">
                    <a data-bs-target="#purchaseOrderMenu" data-bs-toggle="collapse" class="sidebar-link ">
                        <i class="align-middle me-2 fas fa-clone"></i>
                        <span class="align-middle">Purchase Order</span>
                    </a>

                    <ul id="purchaseOrderMenu"
                        class="sidebar-dropdown list-unstyled collapse {{ $isPurchaseOrderActive ? 'show' : '' }}"
                        data-bs-parent="#procurementMenu">
                        @can('purchaseOrder_create')
                            <li
                                class="sidebar-item {{ request()->routeIs('purchaseOrder.listForMerge') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('purchaseOrder.listForMerge') }}">Create
                                    PO</a>
                            </li>
                        @endcan

                        @can('purchaseOrder_list')
                            <li class="sidebar-item {{ request()->routeIs('po.list') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('po.list') }}">PO List</a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @php
                $isStockActive = Route::is('stock.*') || Route::is('stock.out.*') || Route::is('stock.check');
            @endphp
            <li class="sidebar-item {{ $isStockActive ? 'active' : '' }}">
                <a data-bs-target="#stock" data-bs-toggle="collapse"
                    class="sidebar-link {{ $isStockActive ? '' : 'collapsed' }}">
                    <i class="align-middle me-2 fas fa-clone"></i>
                    <span class="align-middle">Stocks</span>
                </a>
                <ul id="stock" class="sidebar-dropdown list-unstyled collapse {{ $isStockActive ? 'show' : '' }}"
                    data-bs-parent="#sidebar">
                    @can('stock_view')
                        <li class="sidebar-item {{ Route::is('stock.index') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('stock.index') }}'>Stock In</a>
                        </li>
                    @endcan
                    @can('stockOut_view')
                        <li class="sidebar-item {{ Route::is('stock.out.index') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('stock.out.index') }}'>Stock Out</a>
                        </li>
                    @endcan
                    @can('stockCheck_view')
                        <li class="sidebar-item {{ Route::is('stock.check') ? 'active' : '' }}">
                            <a class='sidebar-link' href='{{ route('stock.check') }}'>Stock Check</a>
                        </li>
                    @endcan
                </ul>
            </li>
            {{-- @endcanany --}}
            <!-- HR Start -->
            @php
                $user = auth()->user();
            @endphp

            {{-- @if ($user && $user->email === 'hr@gmail.com') --}}
                {{-- @canany(['employee_view', 'bank_view', 'employee_department_view', 'designations']) --}}
                @php
                    $isHrActive =
                        request()->routeIs('employee.*') ||
                        request()->routeIs('attendances.*') ||
                        request()->routeIs('companyBank.*') ||
                        request()->routeIs('tax.*') ||
                        request()->routeIs('fuel.*') ||
                        request()->routeIs('application.*') ||
                        request()->routeIs('payroll.*') ||
                        request()->routeIs('employee_departments.*') ||
                        request()->routeIs('designations.*');
                @endphp

                <li class="sidebar-item mt-1 {{ $isHrActive ? 'active' : '' }}">
                    <a data-bs-target="#hr" data-bs-toggle="collapse"
                        class="sidebar-link {{ $isHrActive ? '' : 'collapsed' }}">
                        <i class="align-middle me-2 fas fa-fw fa-users"></i>
                        <span class="align-middle">HR</span>
                    </a>
                    <ul id="hr"
                        class="sidebar-dropdown list-unstyled collapse {{ $isHrActive ? 'show' : '' }}"
                        data-bs-parent="#sidebar">

                        @can('employee_view')
                            <li class="sidebar-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('employee.index') }}'>Employees</a>
                            </li>
                        @endcan
                        @can('employee_view')
                            <li class="sidebar-item {{ request()->routeIs('attendence.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('attendances.index') }}'>Attendence</a>
                            </li>
                        @endcan
                        @can('employee_view')
                            <li class="sidebar-item {{ request()->routeIs('leave.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('leave.show') }}'>Leave Appication</a>
                            </li>
                        @endcan
                        @can('bank_view')
                            <li class="sidebar-item {{ request()->routeIs('companyBank.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('company.bank.index') }}'>Company Bank</a>
                            </li>
                        @endcan
                        @can('bank_view')
                            <li class="sidebar-item {{ request()->routeIs('tax.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('tax.index') }}'>Tax</a>
                            </li>
                        @endcan
                        @can('bank_view')
                            <li class="sidebar-item {{ request()->routeIs('fuel.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('fuel.index') }}'>Fuel Report</a>
                            </li>
                        @endcan
                        @can('bank_view')
                            <li class="sidebar-item {{ request()->routeIs('application.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('application.index') }}'>Job Application</a>
                            </li>
                        @endcan
                        @can('payroll_view')
                            <li class="sidebar-item {{ request()->routeIs('payrolls.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('payrolls.index') }}'>Payroll</a>
                            </li>
                        @endcan

                        @can('employee_department_view')
                            <li class="sidebar-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('employee_departments.index') }}'>Departments</a>
                            </li>
                        @endcan

                        @can('designation_view')
                            <li class="sidebar-item {{ request()->routeIs('designations.*') ? 'active' : '' }}">
                                <a class='sidebar-link' href='{{ route('designations.index') }}'>Designations</a>
                            </li>
                        @endcan
                    </ul>
                </li>
                {{-- @endcanany --}}
            {{-- @endif --}}
            <!-- HR End -->


        </ul>
    </div>
</nav>
