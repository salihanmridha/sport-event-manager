<nav class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6">
    <div class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto">
        <button class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent" type="button" onclick="toggleNavbar('example-collapse-sidebar')">
            <i class="fas fa-bars"></i>
        </button>
        <a class="md:block text-left md:pb-2 text-blueGray-700 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0" href="{{ route('admin.home') }}">
            {{ trans('panel.site_title') }}
        </a>
        <div class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded hidden" id="example-collapse-sidebar">
            <div class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-blueGray-300">
                <div class="flex flex-wrap">
                    <div class="w-6/12">
                        <a class="md:block text-left md:pb-2 text-blueGray-700 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0" href="{{ route('admin.home') }}">
                            {{ trans('panel.site_title') }}
                        </a>
                    </div>
                    <div class="w-6/12 flex justify-end">
                        <button type="button" class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent" onclick="toggleNavbar('example-collapse-sidebar')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>



            <!-- Divider -->
            <div class="flex md:hidden">
                @if(file_exists(app_path('Http/Livewire/LanguageSwitcher.php')))
                    <livewire:language-switcher />
                @endif
            </div>
            <hr class="mb-6 md:min-w-full" />
            <!-- Heading -->

            <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                <li class="items-center">
                    <a href="{{ route("admin.home") }}" class="{{ request()->is("admin") ? "sidebar-nav-active" : "sidebar-nav" }}">
                        <i class="fas fa-tv"></i>
                        {{ trans('global.dashboard') }}
                    </a>
                </li>

                @can('user_management_access')
                    <li class="items-center">
                        <a class="has-sub {{ request()->is("admin/permissions*")||request()->is("admin/roles*")||request()->is("admin/users*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="#" onclick="window.openSubNav(this)">
                            <i class="fa-fw fas c-sidebar-nav-icon fa-users">
                            </i>
                            {{ trans('cruds.userManagement.title') }}
                        </a>
                        <ul class="ml-4 subnav hidden">
                            @can('permission_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/permissions*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.permissions.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-unlock-alt">
                                        </i>
                                        {{ trans('cruds.permission.title') }}
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/roles*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.roles.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-briefcase">
                                        </i>
                                        {{ trans('cruds.role.title') }}
                                    </a>
                                </li>
                            @endcan

                            @can('user_access')
                                <li class="items-center">
                                    <a class="{{ (request()->is("admin/users*") && request()->get("type") == 'apps') ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.users.index", ['type' => 'apps']) }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-user">
                                        </i>
                                        {{ trans('cruds.user.apps.title') }}
                                    </a>
                                </li>
                            @endcan

                            @can('user_access')
                                <li class="items-center">
                                    <a class="{{ (request()->is("admin/users*")  && request()->get("type") == 'cms') ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.users.index", ['type' => 'cms']) }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-user">
                                        </i>
                                        {{ trans('cruds.user.cms.title') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('content_access')
                    <li class="items-center">
                        <a class="has-sub {{ request()->is("admin/teams*")||request()->is("admin/events*") || request()->is("admin/venues*")|| request()->is("admin/announcements*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="#" onclick="window.openSubNav(this)">
                            <i class="fa-fw fas c-sidebar-nav-icon fa-cogs">
                            </i>
                            {{ trans('cruds.content.title') }}
                        </a>
                        <ul class="ml-4 subnav hidden">
                            @can('team_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/teams*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.teams.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                        </i>
                                        {{ trans('cruds.team.title') }}
                                    </a>
                                </li>
                            @endcan
                                @can('event_access')
                                    <li class="items-center">
                                        <a class="has-sub {{ request()->is("admin/events*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="#" onclick="window.openSubNav(this)">
                                            <i class="fa-fw fas c-sidebar-nav-icon fa-cogs">
                                            </i>
                                            {{ trans('cruds.event.title') }}
                                        </a>
                                        <ul class="ml-4 subnav hidden">
                                            <li class="items-center">
                                                <a class="{{ request()->get('eventType') == 'pickup' ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.events.index", ['eventType' => 'pickup']) }}">
                                                    <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                                    </i>
                                                    {{ trans('cruds.event.pickup.title') }}
                                                </a>
                                            </li>
                                            <li class="items-center">
                                                <a class="{{ request()->get('eventType') == 'session' ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.events.index", ['eventType' => 'session']) }}">
                                                    <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                                    </i>
                                                    {{ trans('cruds.event.session.title') }}
                                                </a>
                                            </li>
                                            <li class="items-center">
                                                <a class="{{ request()->get('eventType') == 'sport' ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.events.index", ['eventType' => 'sport']) }}">
                                                    <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                                    </i>
                                                    {{ trans('cruds.event.sport.title') }}
                                                </a>
                                            </li>
                                            <li class="items-center">
                                                <a class="{{ request()->get('eventType') == 'league' ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.events.index", ['eventType' => 'league']) }}">
                                                    <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                                    </i>
                                                    {{ trans('cruds.event.league.title') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endcan

                            @can('event_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/announcements*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.announcements.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                        </i>
                                        {{ trans('cruds.announcement.title') }}
                                    </a>
                                </li>
                            @endcan
                            @can('venue_access')
                                    <li class="items-center">
                                        <a class="{{ request()->is("admin/venues*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.venues.index") }}">
                                            <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                            </i>
                                            {{ trans('cruds.venue.title') }}
                                        </a>
                                    </li>
                                @endcan
                        </ul>
                    </li>
                @endcan
                @can('system_access')
                    <li class="items-center">
                        <a class="has-sub {{ request()->is("admin/sports*")||request()->is("admin/countries*") || request()->is("admin/currencies*") || request()->is("admin/relationship*")|| request()->is("admin/skills*")|| request()->is("admin/venue-type*") || request()->is("admin/organization-role*")? "sidebar-nav-active" : "sidebar-nav" }}" href="#" onclick="window.openSubNav(this)">
                            <i class="fa-fw fas c-sidebar-nav-icon fa-cogs">
                            </i>
                            {{ trans('cruds.system.title') }}
                        </a>
                        <ul class="ml-4 subnav hidden">
                            @can('sport_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/sports*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.sports.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                        </i>
                                        {{ trans('cruds.sport.title') }}
                                    </a>
                                </li>
                            @endcan
                            @can('country_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/countries*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.countries.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                        </i>
                                        {{ trans('cruds.country.title') }}
                                    </a>
                                </li>
                            @endcan
                            @can('currency_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/currencies*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.currencies.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                        </i>
                                        {{ trans('cruds.currency.title') }}
                                    </a>
                                </li>
                            @endcan
                            @can('relationship_access')
                                <li class="items-center">
                                    <a class="{{ request()->is("admin/relationship*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.relationship.index") }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                        </i>
                                        {{ trans('cruds.contactRelationship.title') }}
                                    </a>
                                </li>
                            @endcan
                            @can('skill_access')
                            <li class="items-center">
                                <a class="{{ request()->is("admin/skills*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.skills.index") }}">
                                    <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                    </i>
                                    {{ trans('cruds.skill.title') }}
                                </a>
                            </li>
                            @endcan

                            @can('organization_access')
                            <li class="items-center">
                                <a class="{{ request()->is("admin/organization-role*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.organization-role.index") }}">
                                    <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                    </i>
                                    {{ trans('cruds.organizationRole.title') }}
                                </a>
                            </li>
                            @endcan

                            @can('venue_type_access')
                            <li class="items-center">
                                <a class="{{ request()->is("admin/venue-type*") ? "sidebar-nav-active" : "sidebar-nav" }}" href="{{ route("admin.venue-types.index") }}">
                                    <i class="fa-fw c-sidebar-nav-icon fas fa-cogs">
                                    </i>
                                    {{ trans('cruds.venue-type.title') }}
                                </a>
                            </li>
                            @endcan

                        </ul>
                    </li>
                @endcan


                @if(auth()->user()->venue)
                    {{-- Venues --}}
                    <li class="items-center">
                        <a href="{{ route('admin.venues.show', auth()->user()->venue) }}" class="{{ request()->is("admin/venues*") ? "sidebar-nav-active" : "sidebar-nav" }}">
                            <i class="fa-fw fas c-sidebar-nav-icon fa-cogs"></i>
                            {{ trans('cruds.venue.my_venue') }}
                        </a>
                    </li>

                    {{-- Reservations --}}
                    <li class="items-center">
                        <a href="{{ route('admin.reservations.index')}}" class="{{ request()->is("admin/reservations*") ? "sidebar-nav-active" : "sidebar-nav" }}">
                            <i class="fa-fw fas c-sidebar-nav-icon fa-cogs"></i>
                            {{ trans('cruds.reservation.title') }}
                        </a>
                    </li>
                @endif

                {{-- Profiles --}}
                @if(file_exists(app_path('Http/Controllers/Auth/UserProfileController.php')))
                    @can('auth_profile_edit')
                        <li class="items-center">
                            <a href="{{ route("profile.show") }}" class="{{ request()->is("profile") ? "sidebar-nav-active" : "sidebar-nav" }}">
                                <i class="fa-fw c-sidebar-nav-icon fas fa-user-circle"></i>
                                {{ trans('global.my_profile') }}
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="items-center">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();" class="sidebar-nav">
                        <i class="fa-fw fas fa-sign-out-alt"></i>
                        {{ trans('global.logout') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
