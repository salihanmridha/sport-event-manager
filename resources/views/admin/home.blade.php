@extends('layouts.admin')
@section('content')

    <div class="row">
        <div class="card bg-blueGray-100 w-full">
            <div class="card-header">
                <div class="card-row">
                    <h6 class="card-title">
                        Dashboard
                    </h6>

                </div>
                <div class="card-row">
                    <div class="px-4 md:px-10 mx-auto w-full">
                        <div>
                            <div class="flex flex-wrap">
                                {{-- User management--}}
                                @can('user_management_access')
                                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                                    <div
                                        class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">
                                                        Users
                                                    </h5>
                                                    <span class="font-semibold text-xl text-blueGray-700">
                                                {{ number_format($countUser + $countAdmin) }}
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                                        <i class="far fa-chart-bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap mr-2">User app</span>
                                                <span class="text-emerald-500">{{ number_format($countUser) }}</span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2">Admin</span>
                                                <span class="text-emerald-500">{{ number_format($countAdmin) }}</span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2"></span>
                                                <span class="text-emerald-500"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                                {{-- Event management--}}
                                @can('event_management_access')
                                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                                    <div
                                        class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">
                                                        Events
                                                    </h5>
                                                    <span class="font-semibold text-xl text-blueGray-700">
                                                    {{ number_format($countEventOnGoing + $countEventComplete + $countEventCancel) }}
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                                        <i class="fas fa-chart-pie"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap mr-2">On going</span>
                                                <span class="text-emerald-500">{{ number_format($countEventOnGoing) }}</span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2">Completed</span>
                                                <span class="text-emerald-500">{{ number_format($countEventComplete) }}</span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2">Canceled</span>
                                                <span class="text-emerald-500">{{ number_format($countEventCancel) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                                    <div
                                        class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">
                                                        Sports
                                                    </h5>
                                                    <span class="font-semibold text-xl text-blueGray-700">
                                                    {{ number_format($countSport) }}
                                                    </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                                        <i class="far fa-chart-bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap mr-2"></span>
                                                <span class="text-emerald-500"></span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2"></span>
                                                <span class="text-emerald-500"></span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2"></span>
                                                <span class="text-emerald-500"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                                    <div
                                        class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">
                                                        Traffic
                                                    </h5>
                                                    <span class="font-semibold text-xl text-blueGray-700">
                                                350,897
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                                        <i class="far fa-chart-bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap mr-2"></span>
                                                <span class="text-emerald-500"></span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2"></span>
                                                <span class="text-emerald-500"></span>
                                            </p>
                                            <p class="text-sm text-blueGray-400 mt-1">
                                                <span class="whitespace-nowrap  mr-2"></span>
                                                <span class="text-emerald-500"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <p class="pt-3">You are logged in!</p>
            </div>
        </div>
    </div>

@endsection
