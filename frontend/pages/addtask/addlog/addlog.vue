<template>
	<view class="addlog">
		<view class="header">
			<uv-icon name="arrow-left" size="36rpx" color="#fff" @click="goBack"></uv-icon>
			<text class="header-text">新增场内巡视</text>
			<MoreBtn class="more-btn"></MoreBtn>
		</view>

		<uv-form>
			<view class="log-form">
				<uv-form-item borderBottom>
					<view class="form-item-time">
						<text>发现日期</text>
						<text>{{ recordTime }}</text>
					</view>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-area">
						<text>区域</text>
						<view class="form-item-area-select" @click="open('area')">
							<text :class="selectArea == '请选择' ? '' : 'selected'">{{ selectArea }}</text>
							<uv-icon name="arrow-right"></uv-icon>
						</view>
					</view>
					<uv-picker ref="areaPicker" :columns="areaList" @confirm="areaConfirm"></uv-picker>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-species">
						<text>鸟种</text>
						<view class="form-item-species-select" @click="open('species')">
							<text :class="selectSpeciesr == '请选择' ? '' : 'selected'">{{ selectSpeciesr }}</text>
							<uv-icon name="arrow-right"></uv-icon>
						</view>
					</view>
					<uv-picker ref="speciesPicker" :columns="speciesList" @confirm="speciesConfirm"></uv-picker>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-count">
						<text>鸟种数量（只）</text>
						<view class="form-item-count-box">
							<uv-input v-model="minCount" placeholder="最小值" border="none" type="number" inputAlign="center"></uv-input>
							<text>~</text>
							<uv-input v-model="maxCount" placeholder="最大值" border="none" type="number" inputAlign="center"></uv-input>
						</view>
					</view>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-height">
						<text>飞行高度（米m）</text>
						<view class="form-item-height-box">
							<uv-input v-model="minHeight" placeholder="最小值" border="none" type="number" inputAlign="center"></uv-input>
							<text>~</text>
							<uv-input v-model="maxHeight" placeholder="最大值" border="none" type="number" inputAlign="center"></uv-input>
						</view>
					</view>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-activity">
						<view class="form-item-activity-text">
							<text>鸟类活动行为</text>
							<text>请选择</text>
						</view>
						<view class="form-item-activity-checkbox">
							<uv-checkbox-group v-model="selectedActivities" placement="column">
								<uv-checkbox v-for="(item, index) in activityList" :key="index" :name="item.action"
									:label="item.action" :customStyle="{ marginBottom: '24rpx' }"
									size="30rpx"></uv-checkbox>
							</uv-checkbox-group>
						</view>
					</view>
				</uv-form-item>
				<uv-form-item borderBottom>
					<view class="form-item-deterrent">
						<view class="form-item-deterrent-text">
							<text>采取的驱鸟措施</text>
							<text>请选择</text>
						</view>
						<view class="form-item-deterrent-checkbox">
							<uv-checkbox-group v-model="selectedDeterrents" placement="column">
								<uv-checkbox v-for="(item, index) in deterrentList" :key="index" :name="item.measures"
									:label="item.measures" :customStyle="{ marginBottom: '24rpx' }"
									size="30rpx"></uv-checkbox>
							</uv-checkbox-group>
						</view>
					</view>
				</uv-form-item>
			</view>
			<view class="bottom-placeholder"></view>
			<view class="log-submit-btn">
				<uv-button text="保存巡视记录" shape="circle" color="#4B9EFF" @click="submit"
					:customStyle="{ color: '#fff', fontWeight: 'bold', width: '600rpx' }"></uv-button>
			</view>
		</uv-form>

	</view>
</template>

<script>
import MoreBtn from '@/components/MoreBtn/MoreBtn.vue'
import { stampToDateTime } from '@/utils/dateFormat.js'
import { addRecord, getBirds } from '@/utils/inspectionApi.js'
export default {
	components: {
		MoreBtn
	},
	data() {
		return {
			taskId: '',
			recordTime: '',
			minCount: '',
			maxCount: '',
			minHeight: '',
			maxHeight: '',
			birdId: 0,
			birdOptions: [],
			selectedActivities: [],
			selectedDeterrents: [],
			activityList: [
				{
					action: '跑道滑行道道面停留',
				},
				{
					action: '跑道滑行道上空飞行',
				},
				{
					action: '跑道端安全区飞行觅食',
				},
			],
			deterrentList: [
				{ measures: '音频' },
				{ measures: '猎枪' },
			],
			areaList: [
				['A', 'B', 'C', 'D']
			],
			speciesList: [[]],
			selectArea: '请选择',
			selectSpeciesr: '请选择'
		};
	},
	async onLoad(options) {
		this.taskId = options.taskId || ''
		const now = stampToDateTime(Date.now())
		this.recordTime = `${now.date} ${now.time}`
		try {
			this.birdOptions = await getBirds()
			this.speciesList = [this.birdOptions.map(item => item.name)]
		} catch (e) {}
	},
	methods: {
		stampToDateTime,
		goBack() {
			const pages = getCurrentPages();
			if (pages.length > 1) {
				uni.navigateBack();
			} else {
				uni.switchTab({
					url: '/pages/tabbar/index/index'
				});
			}
		},
		open(type) {
			if (type === 'area') {
				this.$refs.areaPicker.open();
			} else if (type === 'species') {
				this.$refs.speciesPicker.open();
			}
		},
		areaConfirm(e) {
			this.selectArea = e.value[0];
		},
		speciesConfirm(e) {
			this.selectSpeciesr = e.value[0];
			const bird = this.birdOptions.find(item => item.name === this.selectSpeciesr)
			this.birdId = bird ? bird.id : 0
		},
		async submit() {
			if (this.selectArea === '请选择' || !this.birdId || !this.minCount || !this.maxCount || !this.minHeight || !this.maxHeight) {
				uni.showToast({ title: '请完整填写必填项', icon: 'none' })
				return
			}
			try {
				await addRecord(this.taskId, {
					time: this.recordTime,
					area: this.selectArea,
					bird_id: this.birdId,
					min_num: Number(this.minCount),
					max_num: Number(this.maxCount),
					min_height: Number(this.minHeight),
					max_height: Number(this.maxHeight),
					active_type: this.selectedActivities,
					cuoshi: this.selectedDeterrents
				})
				uni.showToast({ title: '记录保存成功', icon: 'success' })
				setTimeout(() => uni.switchTab({ url: '/pages/tabbar/log/log' }), 400)
			} catch (e) {}
		}
	}
}
</script>

<style lang="scss" scoped>
.header {
	width: 100%;
	height: 88rpx;
	display: flex;
	align-items: center;
	padding-left: 14rpx;
	margin-bottom: 14rpx;

	.header-text {
		color: #fff;
		font-size: $font36;
		font-weight: blod;
		margin-left: 10rpx;
	}

	.more-btn {
		margin-left: auto;
		margin-right: 12rpx;
	}
}

.log-form {
	width: 690rpx;
	background-color: #fff;
	margin: 0 auto;
	border-radius: 10rpx;

	.form-item-time {
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin: 10rpx 54rpx 0 60rpx;

		text:nth-child(1) {
			color: #222;
			font-size: $font28;
		}

		text:nth-child(2) {
			color: #000;
			font-size: $font28;
		}
	}

	.form-item-area,
	.form-item-species {
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-left: 42rpx;
		margin-right: 40rpx;

		> :first-child {
			color: #222;
			font-size: $font28;

			&::before {
				content: "*";
				color: #ee4444;
				margin-right: 8rpx;
			}
		}

		.form-item-area-select,
		.form-item-species-select {
			display: flex;
			gap: 20rpx;
			align-items: center;
			color: #999;
			font-size: $font28;
		}
	}

	.form-item-count,
	.form-item-height {
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		margin-left: 42rpx;
		margin-right: 40rpx;

		> :first-child {
			color: #222;
			font-size: $font28;

			&::before {
				content: "*";
				color: #ee4444;
				margin-right: 8rpx;
			}
		}

		.form-item-count-box,
		.form-item-height-box {
			display: flex;
			align-items: center;
			gap: 10rpx;
			width: 300rpx;
		}
	}

	.form-item-activity,
	.form-item-deterrent {
		width: 100%;

		.form-item-activity-text,
		.form-item-deterrent-text {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin: 0 54rpx 28rpx 42rpx;

			text:nth-child(1) {
				color: #222;
				font-size: $font28;

				&::before {
					content: "*";
					color: #ee4444;
					margin-right: 8rpx;
				}
			}

			text:nth-child(2) {
				color: #999;
				font-size: $font28;
			}
		}

		.form-item-activity-checkbox,
		.form-item-deterrent-checkbox {
			margin-left: 48rpx;
		}
	}

}

::v-deep .uv-form-item__body {
	padding: 28rpx 0;
}

::v-deep .uv-line {
	width: 610rpx !important;
	margin: 0 auto !important;
}

.bottom-placeholder {
	height: 200rpx;
}

.log-submit-btn {
	position: fixed;
	left: 0;
	right: 0;
	bottom: 0;
	height: 148rpx;
	background-color: #fff;
	display: flex;
	justify-content: center;
	align-items: center;
	border-top-left-radius: 20rpx;
	border-top-right-radius: 20rpx;
}

.selected {
	color: #000 !important;
}
</style>
