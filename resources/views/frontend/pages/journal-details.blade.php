@php
    $app_local    = get_default_language_code();
@endphp
@extends('frontend.layouts.master')

@section('content')
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Blog
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <section class="blog blog-details pt-150 pb-80">
        <div class="container mx-auto">
            <div class="row">
                <div class="col-lg-8">
                    <div class="right-content">
                        <img src="{{ get_image($journal->data->image ?? null ,'site-section')}}" alt="image">
                        <h3>{{ $journal->data->language->$app_local->title ?? "" }}</h3>
                        <div class="details">
                            <p>{!! $journal->data->language->$app_local->description ?? "" !!}</p>
                            
                        </div>
                        <div class="hr"></div>
                        @php
                            $tags    = $journal->data->language->$app_local->tags ?? [];
                        @endphp
                        <div class="tag">
                            @foreach ($tags as $item)
                                <a href="javascript:void(0)">{{ $item}}</a>
                            @endforeach 
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 left-content pt-lg-0 pt-md-0 pt-3">
                    <div class="categories">
                        <h3>{{ __("Categories") }}</h3>
                        <div class="hr"></div>
                        <div>
                            @foreach ($category as $item)
                                <div class="d-flex justify-content-between item">
                                    <a href="{{ setRoute('frontend.journal.category',$item->slug) }}">{{ $item->name->language->$app_local->name ?? '' }}</a>
                                    <span>{{ $item->journals_count}}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3>{{ __("Recent Posts") }}</h3>
                        <div class="hr"></div>
                        <div>
                            @foreach ($recent_posts as $item)
                                <div class="d-flex mb-4">
                                    <div class="me-3 blog-sidebar-img">
                                        <a href="{{ setRoute('frontend.journal.details',$item->slug) }}"><img src="{{ get_image($item->data->image ?? null , 'site-section')}}" alt="image"></a>
                                    </div>
                                    <div class="recent-content">
                                        @php
                                            $date = $item->created_at ?? "";
                                            $formattedDate = date('M d, Y', strtotime($date));
                                        @endphp
                                        <p>{{ $formattedDate ?? "" }}</p>
                                        <a href="{{ setRoute('frontend.journal.details',$item->slug) }}"><h4>{{ Str::words($item->data->language->$app_local->title ?? '','5','...') ?? "" }}</h4></a>
                                    </div>
                                </div>
                            @endforeach  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Blog
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@endsection