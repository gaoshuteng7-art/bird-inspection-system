<template>
	<view id="page">
		<image src="/static/bg.jpg" mode="widthFix" id="bg"></image>
		<scroll-view id="container" :scroll-y="true" @scrolltolower="tolower"><slot></slot></scroll-view>
	</view>
</template>

<script>
	export default {
		name:"bg",
		emits: ['tolower'],
		data() {
			return {
				
			};
		},
		methods:{
			tolower(){
				this.$emit('tolower')
			}
		}
	}
</script>

<style lang="scss">
#page{
	height: 100%;
	position: relative;
	overflow: hidden;
	#bg{
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		z-index: 1;
	}
	#container{
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		z-index: 2;
		height: 100%;
	}
}
</style>