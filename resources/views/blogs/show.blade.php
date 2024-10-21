@extends('layouts.frontend')

@section('content')
    <!--==================== HOME ====================-->
    <section>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <section class="islands swiper-slide">
                    <img src="{{ Storage::url($blog->image) }}" alt="" class="islands__bg" />

                    <div class="islands__container container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle">{{ $blog->title }}</h2>
                            <h1 class="islands__title">{{ date('d M Y', strtotime($blog->created_at)) }}</h1>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

    <!-- blog -->
    <section class="blog section" id="blog">
        <div class="blog__container container">
            <div class="content__container">
                <div class="blog__detail">
                    {!! $blog->description !!}
                    <div class="blog__footer" style="margin-top: 2rem;">
                        <div class="blog__reaction">{{ date('d M Y', strtotime($blog->created_at)) }}</div>
                        <div class="blog__reaction">
                            <i class="bx bx-show"></i>
                            <span>{{ $blog->reads }}</span>
                        </div>
                    </div>

                    <div class="shareArticle">
                        <div class="shareSocial">
                            <h3 class="socialTitle">Share Article <span><i class="bx bx-share-alt"></i></span>:</h3>
                            <ul class="socialList">
                                <li>
                                    <a
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ route('blog.show', $blog->slug) }}">
                                        <i class="bx bxl-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="https://twitter.com/intent/tweet?text=YOUR_TITLE&url={{ route('blog.show', $blog->slug) }}">
                                        <i class="bx bxl-twitter"></i>
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="">
                                        <i class="bx bxl-instagram"></i>
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="whatsapp://send?text={{ $blog->title }}, Visit more {{ route('blog.show', $blog->slug) }}"
                                        data-action="share/whatsapp/share" target="_blank">
                                        <i class="bx bxl-whatsapp"></i>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <button class="copy-button" data-url="{{ route('blog.show', $blog->slug) }}">
                                            <i class="bx bx-copy"></i>
                                        </button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="package-travel">
                    <h3>Favorite Category</h3>
                    <ul>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <h3 style="margin-bottom: 1rem">Popular Package</h3>
                    @foreach ($travel_packages as $travel_package)
                        <article class="popular__card" style="margin-bottom: 1rem">
                            <a href="{{ route('travel_package.show', $travel_package->slug) }}">
                                <img src="{{ Storage::url($travel_package->galleries->first()->images) }}" alt=""
                                    class="popular__img" />
                                <div class="popular__data">
                                    <h2 class="popular__price"><span>$</span>{{ number_format($travel_package->price, 2) }}
                                    </h2>
                                    <h3 class="popular__title">{{ $travel_package->location }}</h3>
                                    <p class="popular__description">{{ $travel_package->type }}</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="status-container"></div>

    <section class="blog" id="blog">
        <div class="blog__container container">
            <span class="section__subtitle" style="text-align: center">Related Blog</span>
            <h2 class="section__title" style="text-align: center">
                Find The Best Articles
            </h2>

            <div class="blog__content grid">
                @foreach ($relatedBlogs as $relatedBlog)
                    <article class="blog__card">
                        <div class="blog__image">
                            <img src="{{ Storage::url($relatedBlog->image) }}" alt="" class="blog__img" />
                            <a href="{{ route('blog.show', $relatedBlog->slug) }}" class="blog__button">
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>

                        <div class="blog__data">
                            <h2 class="blog__title">{{ $relatedBlog->title }}</h2>
                            <p class="blog__description">
                                {{ $relatedBlog->excerpt }}
                            </p>

                            <div class="blog__footer">
                                <div class="blog__reaction">{{ date('d M Y', strtotime($relatedBlog->crated_at)) }}</div>
                                <div class="blog__reaction">
                                    <i class="bx bx-show"></i>
                                    <span>{{ $relatedBlog->reads }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        .contact-area {
            display: none;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('.copy-button');

            copyButtons.forEach(copyButton => {
                copyButton.addEventListener('click', event => {
                    const url = copyButton.getAttribute('data-url');
                    const clipboard = new ClipboardJS(copyButton, {
                        text: function() {
                            return url;
                        }
                    });

                    clipboard.on('success', function(e) {
                        console.log('URL copied to clipboard!');
                        showStatusMessage(true);
                        clipboard.destroy(); // Cleanup
                    });

                    clipboard.on('error', function(e) {
                        console.error('Failed to copy URL to clipboard!');
                        showStatusMessage(false);
                        clipboard.destroy(); // Cleanup
                    });

                    clipboard.onClick(event); // Manually trigger the clipboard action
                });
            });
        });

        function showStatusMessage(isSuccess) {
            const statusContainer = document.querySelector('.status-container');
            const statusMessage = document.createElement('p');
            statusMessage.textContent = isSuccess ? 'Success copy URL to clipboard!' : 'Failed copy URL to clipboard!';
            statusMessage.classList.add('status-message');

            statusContainer.appendChild(statusMessage);
            statusContainer.style.display = 'block';

            setTimeout(function() {
                statusMessage.remove();
                if (statusContainer.childElementCount === 0) {
                    statusContainer.style.display = 'none';
                }
            }, 3000);
        }
    </script>
@endsection

@push('style-alt')
    <style>
        blockquote {
            border-left: 8px solid #b4b4b4;
            padding-left: 1rem;
        }

        .blog__detail ul li {
            list-style: initial;
        }
    </style>
@endpush
