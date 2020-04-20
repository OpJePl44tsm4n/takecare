<template>
	<div>
		<div v-if="likeCount" title="{{ '${likeCount} people liked this' }}"><i class="fas fa-hand-holding-heart"></i> {{ likeCount }}</div>
		<div @click="toggleBookmark"><i class="{{ bookmarkStatus ? 'fas' : 'far'}} fa-bookmark"></i></div>
	</div>
</template>


<script>

	export default {
		props: {
			likes: {
				type: Array,
				default() {
					return [];
				}
			},
			userBookmarked: {
				type: Boolean,
				default: false
			}
		}, 
		data() {
			return { 
				likeCount: this.likes.length
				bookmarkStatus: this.userBookmarked
			}
		}, 
		methods: {
			toggleBookmark() {
				if(this.bookmarkStatus) {
					// send API request to remove bookmark
					this.likeStatus = false;
					alert('unliked ');
				} else {
					// send API request to add bookmark
					this.likeStatus = true;
					alert('liked');
				}
			}
		}

	};

</script>