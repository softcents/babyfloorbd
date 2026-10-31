@foreach ($reviews as $review)
<article class="review-item">
    <div class="review-item-head">
        <div class="review-avatar">{{ mb_strtoupper(mb_substr($review->name, 0, 1)) }}</div>
        <div>
            <h3 class="review-author">{{ $review->name }}</h3>
            <div class="product-rating">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->ratting)
                        <i class="fas fa-star"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
        </div>
        <time class="review-date" datetime="{{ $review->created_at->format('Y-m-d') }}">
            {{ $review->created_at->format('d M, Y') }}
        </time>
    </div>
    <p class="review-text">{{ $review->review }}</p>
</article>
@endforeach
