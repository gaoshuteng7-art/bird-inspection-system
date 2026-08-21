<template>
	<view class="more">
		<view class="dots">
			<view class="dot"></view>
			<view class="big-dot"></view>
			<view class="dot"></view>
		</view>
		<view class="line"></view>
		<view class="rings">
			<view class="ring"></view>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {

		};
	}
}
</script>

<style lang="scss">
.more {
	display: flex;
	justify-content: space-between;
	align-items: center;
	width: 174rpx;
	height: 64rpx;
	background-color: #fff;
	border-radius: 50rpx;
	padding: 0 26rpx;

	.dots {
		display: flex;
		justify-content: space-between;
		align-items: center;
		width: 40rpx;
		height: 40rpx;
		padding: 0 2rpx;

		.dot {
			width: 8rpx;
			height: 8rpx;
			background-color: #333;
			border-radius: 50%;
		}

		.big-dot {
			width: 12rpx;
			height: 12rpx;
			background-color: #333;
			border-radius: 50%;
		}
	}

	.line {
		width: 2rpx;
		height: 36rpx;
		background: #ECECEC;
	}

	.rings {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 30rpx;
		height: 30rpx;
		border: 4rpx solid #000;
		border-radius: 50%;

		.ring {
			width: 10rpx;
			height: 10rpx;
			background-color: #000;
			border-radius: 50%;
		}
	}
}
</style>