<template>
	<bg @tolower="onPullDownRefresh">
		<view class="index">
			<view class="user">
				<view class="site-weather">
					<view class="site">
						<image src="/static/airplane.png" mode="aspectFit"></image>
						<text>鄂尔多斯机场</text>
					</view>
					<view class="weather">
						<image src="/static/sunny.png" mode="aspectFit"></image>
						<text>晴</text>
						<text>15°C~35°C</text>
					</view>
				</view>
				<view class="user-login">
					<image :src="isLogin ? '/static/avatar.png' : '/static/default-avatar.png'" mode="aspectFit"></image>
					<template v-if="!isLogin">
						<view class="user-info" @click="navigator('/pages/login/login')">
							<text class="default-name">下午好，请先登陆</text>
							<text class="default-text">鸟情调研平台</text>
						</view>
					</template>
					<template v-else>
						<view class="user-info">
							<text class="user-name">欢迎您，张三</text>
							<text class="user-text">巡视员</text>  
						</view>
						<view class="logout-btn"  @click="logout">
							退出登陆
						</view>
					</template>
				</view>
				<view class="task-number">
					<view class="task-item">
						<text class="task-count">{{ stats.month }}</text>
						<text class="task-text">本月巡视</text>
					</view>
					<view class="task-item">
						<text class="task-count">{{ stats.quarter }}</text>
						<text class="task-text">本季度巡视</text>
					</view>
					<view class="task-item">
						<text class="task-count">{{ stats.year }}</text>
						<text class="task-text">本年巡视</text>
					</view>
				</view>
				<image src="/static/sys.png" class="sys-image"></image>
			</view>
		
			<view class="notice-bar">
				<view class="notice-title">
					<text class="notice-text">通知</text>
					<text class="announcement-text">公告</text>
				</view>
				<view class="notice-line"></view>
				<view class="notice-content">鸟情调研系统试运行公告!</view>
				<view class="notice-more">
					<text>更多</text>
					<uv-icon name="arrow-right" color="#999" size="24rpx"></uv-icon>
				</view>
			</view>
		
			<view class="task-bar">
				<view class="task-calendar">
					<text class="task-calendar-left">{{ selectedDate === today ? '今日' : selectedDate }}巡视任务</text>
					<view class="task-calendar-right" @click="openCalendar">
						<image src="/static/calendar.png" mode="aspectFit"></image>
						<text>巡视日历</text>
					</view>
				</view>
				<uv-calendars ref="calendars" @confirm="confirm" />
				<view class="inspection-list">
					<InspectionCard v-if="!isLogin" :isLogin="false" location="场内"></InspectionCard>
					<template v-else>
						<InspectionCard v-for="task in displayedTasks" :key="task.id" :isLogin="true" :task="task"></InspectionCard>
						<view v-if="!displayedTasks.length" class="empty-task">该日期暂无巡视任务</view>
						<view class="add-task" @click="navigator('/pages/addtask/addtask/addtask')">+ 新增巡视计划</view>
					</template>
				</view>
			</view>
		</view>
	</bg>
</template>

<script>
import InspectionCard from '@/components/InspectionCard/InspectionCard.vue'
import { getTasks } from '@/utils/inspectionApi.js'
import { stampToDateTime } from '@/utils/dateFormat.js'
export default {
	components: { InspectionCard },
	data() {
		return {
			isLogin: false,
			tasks: [],
			selectedDate: stampToDateTime(Date.now()).date,
		};
	},
	computed: {
		today() { return stampToDateTime(Date.now()).date },
		displayedTasks() { return this.tasks.filter(item => item.date === this.selectedDate) },
		stats() {
			const now = new Date()
			const year = String(now.getFullYear())
			const month = `${year}-${String(now.getMonth() + 1).padStart(2, '0')}`
			const start = Math.floor(now.getMonth() / 3) * 3 + 1
			return {
				month: this.tasks.filter(item => item.date.startsWith(month)).length,
				quarter: this.tasks.filter(item => { const p = item.date.split('-').map(Number); return p[0] === now.getFullYear() && p[1] >= start && p[1] < start + 3 }).length,
				year: this.tasks.filter(item => item.date.startsWith(year)).length
			}
		}
	},
	async onShow() {
		this.isLogin = Boolean(uni.getStorageSync('user-token'))
		if (this.isLogin) await this.loadTasks()
	},
	methods: {
		async loadTasks() {
			try { this.tasks = await getTasks() } catch (e) { this.tasks = [] }
		},
		openCalendar() {
			console.log('打开日历');
			this.$refs.calendars.open();
		},
		confirm(e) {
			this.selectedDate = e.fulldate
		},
		async onPullDownRefresh(){
			await this.loadTasks()
			uni.stopPullDownRefresh()
		},
		navigator(url){
			uni.navigateTo({
				url
			})
	},
	    logout(){
			if(!this.isLogin){
				uni.showToast({
					title:'当前尚未登陆',
					icon:'none'
				})
				return
			}
			uni.showModal({
				title:'退出登陆',
				content:'确定要退出当前账号吗？',
				confirmText:'退出',
				cancelText:'取消',
				
				success:(res)=>{
					if (!res.confirm){
					return
				}
				uni.removeStorageSync('user-token')
				uni.removeStorageSync('user-info')
				
				this.isLogin = false
				this.tasks = []
				
				uni.reLaunch({
					url: '/pages/login/login'
				})
			}
		})
	}
},		
	
	onReachBottom(){
		console.log('触底')
	}
}
</script>

<style lang="scss" scoped>
.index {
	padding-bottom: var(--window-bottom);
	box-sizing: border-box;
}
.logout-btn{
	margin-left: auto;
	margin-right: 30rpx;
	padding: 12rpx 22rpx;
	color: #ffffff;
	font-size: 24rpx;
	background-color: #ff5a5f;
	border-radius: 30rpx;
}
.user {
	width: 690rpx;
	height: 420rpx;
	background-color: #fff;
	border-radius: 10rpx;
	margin: 16rpx auto 24rpx auto;
	overflow: hidden;

	.site-weather {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin: 26rpx 30rpx 30rpx 24rpx;

		.site {
			display: flex;
			align-items: center;

			image {
				width: 28rpx;
				height: 28rpx;
				margin-right: 10rpx;
			}

			text {
				font-size: $font28;
				color: $text-color;
				margin-left: 8rpx;
			}
		}

		.weather {
			display: flex;
			align-items: center;

			image {
				width: 28rpx;
				height: 28rpx;
			}

			text {
				font-size: $font28;
				color: $text-color;
				margin-left: 8rpx;
			}
		}
	}

	.user-login {
		display: flex;
		align-items: center;
		margin-left: 30rpx;

		image {
			width: 120rpx;
			height: 120rpx;
			border-radius: 50%;
			margin-right: 20rpx;
		}

		.user-info {
			display: flex;
			flex-direction: column;
			margin: 10rpx 0;

			.default-name,
			.user-name {
				font-size: $font36;
				color: #444;
				font-weight: bold;
			}

			.default-text {
				font-size: $font28;
				color: #b4b4b4;
				margin-top: 8rpx;
			}

			.user-text {
				border: 2rpx solid $main-color;
				font-size: $font24;
				color: $main-color;
				width: fit-content;
				padding: 0 12rpx;
				display: flex;
				justify-content: center;
				align-items: center;
				margin-top: 14rpx;
			}
		}
	}

	.task-number {
		height: 104rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 0 72rpx;
		margin-top: 24rpx;

		.task-item {
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 18rpx;
			position: relative;

			.task-count {
				font-size: $font36;
				color: $text-color;
			}

			.task-text {
				color: #999;
				font-size: $font24;

			}

			&:not(:last-child)::after {
				content: '';
				position: absolute;
				right: -60rpx;
				top: 50%;
				transform: translateY(-50%);
				width: 2rpx;
				height: 64rpx;
				background-color: #e6e6e6;
			}
		}
	}

	.sys-image {
		display: block;
		margin: 26rpx auto 14rpx;
		width: 134rpx;
		height: 36rpx;
	}
}

.notice-bar {
	display: flex;
	align-items: center;
	width: 690rpx;
	height: 94rpx;
	background-color: #fff;
	border-radius: 10rpx;
	margin: 0 auto 18rpx auto;
	overflow: hidden;

	.notice-title {
		height: 100%;
		width: 48rpx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		font-size: $font24;
		font-weight: bold;
		margin-left: 30rpx;

		.notice-text {
			color: $text-color;
			white-space: nowrap;
		}

		.announcement-text {
			color: $main-color;
			white-space: nowrap;
		}
	}

	.notice-line {
		width: 2rpx;
		height: 60rpx;
		background-color: #e7e7e7;
		margin-left: 26rpx;
	}

	.notice-content {
		width: 440rpx;
		font-size: $font26;
		color: #666;
		margin-left: 20rpx;
	}

	.notice-more {
		display: flex;
		align-items: center;
		gap: 4rpx;
		margin-left: auto;
		margin-right: 30rpx;

		text {
			font-size: $font20;
			color: #999;
		}
	}
}

.task-bar {
	width: 690rpx;
	min-height: 624rpx;
	background-color: #fff;
	border-radius: 10rpx;
	margin: 0 auto 18rpx auto;

	.task-calendar {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding-top: 28rpx;
		padding-bottom: 30rpx;

		.task-calendar-left {
			font-size: $font28;
			color: $text-color;
			font-weight: bold;
			margin-left: 30rpx;
		}

		.task-calendar-right {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10rpx;
			width: 156rpx;
			height: 54rpx;
			background-color: #f0f0f0;
			margin-right: 30rpx;

			image {
				width: 28rpx;
				height: 28rpx;
			}

			text {
				line-height: 1;
				font-size: $font24;
				color: $text-color;
			}
		}
	}

	.inspection-list {
		display: flex;
		flex-direction: column;
		gap: 30rpx;
		padding-bottom: 30rpx;
	}

	.empty-task { text-align: center; color: #999; padding: 60rpx 0 20rpx; }
	.add-task { width: 300rpx; height: 72rpx; margin: 0 auto; border-radius: 36rpx; background: $main-color; color: #fff; display: flex; align-items: center; justify-content: center; }
}
</style>
