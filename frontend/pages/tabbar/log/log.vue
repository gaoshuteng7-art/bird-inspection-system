<template>
	<bg>
		<view class="log">
			<uv-search v-model="keyword" placeholder="搜索车辆、巡视员" :showAction="false" inputAlign="center" bgColor="#fff"></uv-search>
			<view class="conditional-search">
				<view class="fold-box" :class="{ foldStatus: !showFilter }">
					<view class="conditional-search-date">
						<text class="search-date-text">日期</text>
						<text class="start-date" :class="startDate == '开始日期' ? '' : 'selected'" @click="open('starDate')">{{
							startDate }}</text>
						<uv-calendars ref="startDateCalendars" @confirm="startDateConfirm" />
						<text class="tilde">~</text>
						<text class="end-date" :class="endDate == '结束日期' ? '' : 'selected'" @click="open('endDate')">{{
							endDate }}</text>
						<uv-calendars ref="endDateCalendars" @confirm="endDateConfirm" />
					</view>
					<view class="conditional-search-car">
						<text class="search-car-text">车辆</text>
						<text class="search-car-select" :class="selectCar == '请选择车辆' ? '' : 'selected'"
							@click="open('selectCar')">{{ selectCar }}</text>
						<uv-picker ref="carPicker" :columns="carList" @confirm="selectCarConfirm"></uv-picker>
					</view>
					<view class="conditional-search-inspector">
						<text class="search-inspector-text">巡视员</text>
						<text class="search-inspector-select" :class="selectInspector == '请选择巡视员' ? '' : 'selected'"
							@click="open('selectInspector')">{{ selectInspector }}</text>
						<uv-picker ref="inspectorPicker" :columns="inspectorList"
							@confirm="selectInspectorConfirm"></uv-picker>
					</view>
				</view>
				<view class="conditional-search-action">
					<view class="clear-btn-box" @click="clearSearch">
						<uv-icon name="trash" size="28rpx"></uv-icon>
						<text class="clear-text">清空搜索</text>
					</view>
					<view class="collapse-btn-box" @click="showFilter = !showFilter">
						<text class="collapse-text">{{ showFilter ? '收起' : '展开' }}</text>
						<uv-icon :name="showFilter ? 'arrow-up' : 'arrow-down'" size="28rpx"></uv-icon>
					</view>
					<view class="search-btn" @click="search">
						<text>搜索</text>
					</view>
				</view>
			</view>
			<view class="log-list">
				<LogCard v-for="task in results" :key="task.id" :task="task"></LogCard>
			</view>
			<view v-if="!results.length" class="empty-log">没有符合条件的巡视计划</view>
		</view>
	</bg>
</template>

<script>
import MoreBtn from '@/components/MoreBtn/MoreBtn.vue';
import LogCard from '@/components/LogCard/LogCard.vue';
import { getTasks } from '@/utils/inspectionApi.js';
export default {
	components: {
		MoreBtn,
		LogCard
	},
	data() {
		return {
			tasks: [],
			results: [],
			keyword: '',
			carList: [[]],
			inspectorList: [[]],
			showFilter: true,
			startDate: '开始日期',
			endDate: '结束日期',
			selectCar: '请选择车辆',
			selectInspector: '请选择巡视员'
		};
	},
	async onShow() {
		try {
			this.tasks = await getTasks()
			this.carList = [[...new Set(this.tasks.map(item => item.car))]]
			this.inspectorList = [[...new Set(this.tasks.reduce((all, item) => all.concat(item.inspectors), []))]]
			this.search()
		} catch (e) {
			this.tasks = []
			this.results = []
		}
	},
	methods: {
		open(type) {
			if (type === 'starDate') {
				this.$refs.startDateCalendars.open();
			} else if (type === 'endDate') {
				this.$refs.endDateCalendars.open();
			} else if (type === 'selectCar') {
				this.$refs.carPicker.open();
			} else if (type === 'selectInspector') {
				this.$refs.inspectorPicker.open();
			}
		},
		startDateConfirm(e) {
			this.startDate = e.fulldate;
		},
		endDateConfirm(e) {
			this.endDate = e.fulldate;
		},
		selectCarConfirm(e) {
			this.selectCar = e.value[0];
		},
		selectInspectorConfirm(e) {
			this.selectInspector = e.value[0];
		},
		clearSearch() {
			this.startDate = '开始日期';
			this.endDate = '结束日期';
			this.selectCar = '请选择车辆';
			this.selectInspector = '请选择巡视员';
			this.keyword = '';
			this.search();
		},
		search() {
			const keyword = this.keyword.trim().toLowerCase()
			this.results = this.tasks.filter(task => {
				if (this.startDate !== '开始日期' && task.date < this.startDate) return false
				if (this.endDate !== '结束日期' && task.date > this.endDate) return false
				if (this.selectCar !== '请选择车辆' && task.car !== this.selectCar) return false
				if (this.selectInspector !== '请选择巡视员' && !task.inspectors.includes(this.selectInspector)) return false
				return !keyword || `${task.car} ${task.inspectors.join(' ')}`.toLowerCase().includes(keyword)
			})
		}
	}
}
</script>

<style lang="scss" scoped>
.log {
	padding-bottom: var(--window-bottom);
	box-sizing: border-box;
}

.header {
	width: 100%;
	height: 88rpx;
	position: relative;

	.header-title {
		color: #fff;
		font-size: $font36;
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
	}

	.more-btn {
		position: absolute;
		right: 12rpx;
		top: 50%;
		transform: translateY(-50%);
	}
}

::v-deep .uv-search {
	width: 706rpx;
	height: 76rpx !important;
	margin: 0 auto !important;

	.uv-search__content {
		height: 100% !important;
		border-radius: 10rpx !important;
	}
}

.conditional-search {
	display: flex;
	flex-direction: column;
	width: 706rpx;
	background-color: #fff;
	margin: 18rpx auto 28rpx auto;
	border-radius: 10rpx;

	.fold-box {
		display: flex;
		flex-direction: column;
		gap: 20rpx;
		overflow: hidden;
		max-height: 260rpx;
		transition: max-height 0.6s cubic-bezier(0.08, 0.82, 0.17, 1);

		.conditional-search-date,
		.conditional-search-car,
		.conditional-search-inspector {
			margin-top: 0;
		}

		.conditional-search-date {
			height: 40rpx;
			display: flex;
			align-items: center;
			margin: 20rpx 76rpx 0 38rpx;

			.search-date-text {
				width: 59rpx;
				height: 40rpx;
				font-size: $font28;
				color: $text-color;
				text-align: center;
				margin-right: 76rpx;
			}

			.start-date,
			.end-date {
				width: 160rpx;
				height: 40rpx;
				text-align: center;
				color: #666;
			}

			.tilde {
				color: #52B765;
				margin: 0 62rpx;
			}
		}

		.conditional-search-car {
			height: 40rpx;
			display: flex;
			align-items: center;
			margin-left: 38rpx;

			.search-car-text {
				width: 59rpx;
				height: 100%;
				font-size: $font28;
				color: $text-color;
				text-align: center;
				margin-right: 44rpx;
			}

			.search-car-select {
				width: 512rpx;
				height: 40rpx;
				text-align: center;
				color: #666;
			}
		}

		.conditional-search-inspector {
			height: 40rpx;
			display: flex;
			align-items: center;
			margin-left: 24rpx;

			.search-inspector-text {
				width: 86rpx;
				height: 100%;
				font-size: $font28;
				color: $text-color;
				text-align: center;
				margin-right: 34rpx;
			}

			.search-inspector-select {
				width: 512rpx;
				height: 100%;
				text-align: center;
				color: #666;
			}
		}
	}

	.conditional-search-action {
		height: 48rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin: 14rpx 30rpx 8rpx 38rpx;

		.clear-btn-box {
			height: 36rpx;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 4rpx;

			.clear-text {
				color: #999;
				font-size: $font24;
			}
		}

		.collapse-btn-box {
			height: 36rpx;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 12rpx;

			.collapse-text {
				color: #999;
				font-size: $font24;
			}
		}

		.search-btn {
			width: 100rpx;
			height: 48rpx;
			border-radius: 10rpx;
			border: 1rpx solid $main-color;
			color: $main-color;
			display: flex;
			justify-content: center;
			align-items: center;

			text {
				font-size: $font22;
			}
		}
	}
}

.fold-box.foldStatus {
	max-height: 0;
}

.selected {
	color: #000 !important;
}

.log-list {
	display: flex;
	flex-direction: column;
	gap: 24rpx;
	padding-bottom: 24rpx;
}

.empty-log {
	width: 706rpx;
	margin: 0 auto;
	padding: 70rpx 0;
	text-align: center;
	color: #999;
	background-color: #fff;
	border-radius: 10rpx;
}
</style>
