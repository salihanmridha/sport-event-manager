@extends('layouts.web') @section('content')
<div class="landing-page container" id="full-landing-page">
    <div class="session-header">
        <div class="header-fixed">
            <div class="container">
                <div class="logo">
                    <!-- <img src="{{ asset('images/') }}" alt="" srcset="" /> -->
                </div>
                <div class="link-app">
                    <div class="apple-link">
                        <a href="{{ $linkApp['apple_link'] }}"></a>
                    </div>
                    <div class="android-link">
                        <a href="{{ $linkApp['android_link'] }}"> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="session-footer">
        <div class="footer-fixed">
            <div class="container">
                <div class="left-footer">
                    <span>Made by TechEdge Solutions</span>
                </div>
                <div class="right-footer">
                    <span>
                        &copy; Copyright 2022 PALARO. All Rights Reserved.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="session-sports">
        <div class="body-sport">
            <div class="title-and-desc">
                <h3 class="title-sports">
                    sports <br />
                    without <br />
                    hassle
                </h3>
                <span class="desc-sport">
                    Don’t cut the adrenaline you feel when <br />
                    managing your sports.
                </span>
            </div>
            <div class="rigth-img">
                <img
                    src="{{ asset('images/Rectangle_5.png') }}"
                    alt=""
                    srcset=""
                />
            </div>
        </div>
    </div>

    <div class="session-never-miss session-sports">
        <div class="body-sport">
            <div class="left-content">
                <img
                    class="left-img"
                    src="{{ asset('images/Rectangle_6.png') }}"
                    alt=""
                    srcset=""
                />
                <img
                    class="right-img"
                    src="{{ asset('images/Rectangle_7.png') }}"
                    alt=""
                    srcset=""
                />
            </div>
            <div class="title-and-desc">
                <h3 class="title-sports">
                    Never miss<br />
                    a thing
                </h3>
                <span class="desc-sport">
                    Manage all your sports event in one place.
                </span>
            </div>
        </div>
    </div>

    <div class="session-sports session-alone">
        <div class="body-sport">
            <div class="title-and-desc">
                <h3 class="title-sports">
                    You are <br />
                    not alone
                </h3>
                <span class="desc-sport">
                    A sports community with your friends, <br />
                    coaches, team mates, and opponents.
                </span>
            </div>
            <div class="rigth-img">
                <img
                    src="{{ asset('images/Rectangle_8.png') }}"
                    alt=""
                    srcset=""
                />
            </div>
        </div>
        <div class="bottom-alone">
            <img src="{{ asset('images/Rectangle_9.png') }}" alt="" srcset="" />
        </div>
    </div>

    <div class="session-banner-body"></div>

    <div class="session-link-app session-sports" id="session-link-app">
        <div class="body-link-app">
            <h3 class="title-link-app">
                Download <br />
                & start <br />
                the game
            </h3>
            <div class="app-link-image">
                <a href="{{ $linkApp['apple_link_s1'] }}" class="apple-link-app">
                </a>
                <a href="{{ $linkApp['android_link_s1'] }}" class="android-link-app">
                    
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
