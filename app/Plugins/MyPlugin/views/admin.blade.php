
<!--

 * Admin layout-based Blade template for MyPlugin.
 * This template extends the core admin layout and includes the sidebar and header.
 * The main content section is structured using Tailwind CSS grid utilities.
 
-->

@extends('admin::layouts.admin')

@section('content')

    <!-- Include admin header component -->
    <x-admin.header />
    
    <!-- Include admin sidebar component -->
    <x-admin.side />
    
    <!-- Main content wrapper -->
    <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
        
        <!-- Grid layout for content sections -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
            
            <!-- Left section: Can be used for widgets, stats, or navigation -->
            <div class="bg-white rounded shadow md:col-span-2 overflow-x-auto p-6">
            </div>
            
            <!-- Right section: Primary content area -->
            <div class="bg-white rounded shadow md:col-span-4 overflow-x-auto p-6">
            </div>
        </div>
    </div>

@endsection
