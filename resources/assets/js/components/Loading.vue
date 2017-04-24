<template>
	<div class="loading-mask">
		<div class="loading-spinner">
			<svg viewBox="25 25 50 50" class="circular"><circle cx="50" cy="50" r="20" fill="none" class="path"></circle></svg>
		<p>数据加载中...</p>
		</div>
	</div>
</template>
<style>
	.loading-mask {
	    position: absolute;
	    z-index: 10000;
	    background-color: rgba(52, 50, 60, 0.95);
	    margin: 0;
	    top: 0;
	    right: 0;
	    bottom: 0;
	    left: 0;
	}
	.loading-spinner {
	    top: 50%;
	    margin-top: -21px;
	    width: 100%;
	    text-align: center;
	    position: absolute;
	}
	.loading-spinner p {
		font-size: 16px;
		color: #26b8dc;
	}
	.loading-spinner .circular {
	    width: 42px;
	    height: 42px;
	    animation: loading-rotate 2s linear infinite;
	}
	.loading-spinner .path {
	    animation: loading-dash 1.5s ease-in-out infinite;
	    stroke-dasharray: 90,150;
	    stroke-dashoffset: 0;
	    stroke-width: 2;
	    stroke: #20a0ff;
	    stroke-linecap: round;
	}
	@keyframes loading-rotate {
    to {
        transform: rotate(1turn)
    }
}
@keyframes loading-dash {
    0% {
        stroke-dasharray: 1, 200;
        stroke-dashoffset: 0
    }
    50% {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -40px
    }
    to {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -120px
    }
}
</style>