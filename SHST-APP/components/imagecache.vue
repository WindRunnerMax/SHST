<template name="imagecache">
	<view>

		<image :mode="mode" :style="imageStyle" :src="resource"></image>

	</view>
</template>

<script>
	export default {
		name: "imagecache",
		props: {
			imageStyle: String,
			src: {
				type: String,
				default: '',
				required: true
			},
			saveDirectory: {
				type: String,
				default: '_downloads/'
			},
			mode: String,
			errorImage: String,
			loadingImage: String
		},
		computed: {
			resource() {
				if (!this.isCached && this.error) {
					// 加载失败 
					return this.errorImage;
				} else if (this.isCached) {
					// return "https://windrunner_max.gitee.io/imgpath/SDUST/SHST.jpg";
					// console.log(plus.io.convertLocalFileSystemURL(this.localFile));
					// return plus.io.convertLocalFileSystemURL(this.localFile)
					return this.localFile;
				} else if (!this.isCached && !this.error) {
					// 正在加载
					return this.loadingImage;
				}
			}
		},
		created() {
			console.log(this.src);

			this.init();
		},
		data() {
			return {
				error: false,
				isCached: false,
				localFile: null
			};
		},
		methods: {
			async init() {
				// #ifdef APP-PLUS || APP-PLUS-NVUE
				let isCache = await this.isCache(this.src);
				if (!isCache) {
					let filePath = await this.downloadFile(this.src).catch(e => {
						this.error = true;
					});
					this.isCached = true;
					this.localFile = filePath;
				} else {
					this.isCached = true;
					this.localFile = isCache;
				}
				// #endif
				// #ifndef APP-PLUS || APP-PLUS-NVUE
				this.isCached = true;
				this.localFile = this.src;
				// #endif
			},
			downloadFile(url) {
				return new Promise((resolve, reject) => {
					let savePath = this.saveDirectory + this.getFileName(url);
					let task = plus.downloader.createDownload(
						url, {
							filename: savePath
						},
						(download, status) => {
							if (status == 200) {
								resolve(savePath);
							} else {
								console.log('文件下载失败');
								reject();
							}
						}
					);
					task.start();
				});
			},
			isCache(url) {
				let savePath = this.saveDirectory + this.getFileName(url);
				return new Promise((resolve, reject) => {
					plus.io.resolveLocalFileSystemURL(
						savePath,
						entry => {
							resolve(savePath);
						},
						e => {
							console.log("Cannot Find Download "+savePath);
							resolve(null);
						}
					);
				});
			},
			getFileName(path) {
				let pathArr = path.split("/");
				let n = pathArr.length;
				return pathArr[n - 2] + pathArr[n - 1];
			},
			imageLoadError() {
				// #ifndef APP-PLUS || APP-PLUS-NVUE
				this.localFile = this.errorImage;
				// #endif
			}
		}
	};
</script>

<style>
	.img {
		width: 100%;
		height: 100%;
	}
</style>
