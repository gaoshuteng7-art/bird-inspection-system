<template>
	<view class="inspection-card" :class="task ? 'inspected-card' : ''" @click="openTask">
		<text class="inspection-position">{{ task ? task.location : location }}</text>
		<template v-if="task">
			<view class="inspection-info">
				<view class="info-item"><text>巡视人员</text><text>{{ task.inspectors.join('、') }}</text></view>
				<view class="info-item"><text>使用车辆</text><text>{{ task.car }}</text></view>
				<view class="info-item"><text>巡视记录</text><text>{{ task.records.length }} 条</text></view>
				<view class="detail-info-btn"><uv-icon name="arrow-right" size="32rpx" color="#4B9EFF"></uv-icon></view>
			</view>
		</template>
		<template v-if="!task && !isLogin">
            <text class="login-text">请登陆后开始巡视</text>
            <view class="login-btn" @click="goLogin">
                <text>去登陆</text>
            </view>
        </template>
		<template v-else-if="!task && !isInspection">
            <text class="inspection-text">今日还没有开始巡视</text>
            <view class="inspection-btn">
                <text>去巡视</text>
            </view>
        </template>
		<template v-else-if="!task">
            <view class="inspection-info">
                <view class="info-item">
                    <text>巡视人员</text>
                    <text>张三、李四、王五</text>
                </view>
                <view class="info-item">
                    <text>使用车辆</text>
                    <text>民航 MA12345</text>
                </view>
                <view class="info-item">
                    <text>巡视进度</text>
                    <view class="item-progress">
                        <uv-line-progress :percentage="30" :showText="false" activeColor="#EE4444"
                            height="10rpx"></uv-line-progress>
                    </view>
                </view>
                <view class="detail-info-btn">
                    <uv-icon name="arrow-right" size="32rpx" color="#4B9EFF"></uv-icon>
                </view>
            </view>

        </template>

    </view>
</template>

<script>
export default {
    props: {
        isLogin: {
            type: Boolean,
            default: false
        },
        isInspection: {
            type: Boolean,
            default: false
        },
        location: {
            type: String,
            default: ''
		},
		task: { type: Object, default: null }

    },
    data() {
        return {
        };
    },
    methods: {
        goLogin() {
            uni.navigateTo({
                url: '/pages/login/login'
            });
		},
		openTask() {
			if (this.task) uni.navigateTo({ url: `/pages/addtask/addlog/addlog?taskId=${this.task.id}` })
		}
    }
}
</script>

<style lang="scss" scoped>
.inspected-card {
    background-color: $secondary-color !important;
}

.inspection-card {
    display: flex;
    flex-direction: column;
    width: 640rpx;
    height: 220rpx;
    background-color: #f6f6f6;
    margin: 0 auto;

    .inspection-position {
        font-size: $font28;
        color: $main-color;
        margin-left: 28rpx;
        margin-top: 22rpx;
    }

    .login-text,
    .inspection-text {
        font-size: $font28;
        color: #b4b4b4;
        margin: 0 auto;
    }

    .login-btn,
    .inspection-btn {
        display: flex;
        align-items: center;
        align-content: center;
        justify-content: center;
        width: 174rpx;
        height: 72rpx;
        background-color: $secondary-color;
        margin: 18rpx auto 38rpx auto;

        text {
            font-size: $font26;
            color: $main-color;
            font-weight: bold;
        }
    }

    .inspection-info {
        display: flex;
        flex-direction: column;
        gap: 8rpx;
        position: relative;

        .info-item {
            height: 36rpx;
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 80rpx;
            margin-left: 30rpx;
            margin-top: 8rpx;
            font-size: $font24;

            text:nth-child(1) {
                color: #575757;
            }

            text:nth-child(2) {
                color: $text-color;
            }

            .item-progress {
                width: 400rpx;
            }
        }

        .detail-info-btn {
            position: absolute;
            bottom: 104rpx;
            right: 18rpx;
        }
    }
}
</style>
