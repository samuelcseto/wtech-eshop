document.addEventListener('DOMContentLoaded', function () {
	// User menu functionality
	const userContainer = document.getElementById('userContainer')
	const userMenu = document.getElementById('userMenu')
	let isUserMenuOpen = false

	// Toggle user menu visibility
	function toggleUserMenu(show) {
		userMenu.style.display = show ? 'block' : 'none'
		isUserMenuOpen = show
	}

	// Show user menu on hover
	userContainer.addEventListener('mouseenter', function () {
		toggleUserMenu(true)
	})

	// Hide user menu when mouse leaves the entire container
	userContainer.addEventListener('mouseleave', function (e) {
		// Make sure we're not hovering over the menu itself
		if (!userMenu.contains(e.relatedTarget)) {
			toggleUserMenu(false)
		}
	})

	// Prevent the menu from closing when hovering on the menu
	userMenu.addEventListener('mouseenter', function () {
		toggleUserMenu(true)
	})

	// Handle clicking outside to close the menu
	document.addEventListener('click', function (e) {
		if (isUserMenuOpen && !userContainer.contains(e.target)) {
			toggleUserMenu(false)
		}
	})

	// Shopping cart functionality
	const cartContainer = document.getElementById('cartContainer')
	const cartPreview = document.getElementById('cartPreview')
	let isCartOpen = false

	// Toggle cart preview visibility
	function toggleCart(show) {
		cartPreview.style.display = show ? 'block' : 'none'
		isCartOpen = show
	}

	// Show cart preview on hover
	cartContainer.addEventListener('mouseenter', function () {
		toggleCart(true)
	})

	// Hide cart preview when mouse leaves the entire container
	cartContainer.addEventListener('mouseleave', function (e) {
		// Make sure we're not hovering over the preview itself
		if (!cartPreview.contains(e.relatedTarget)) {
			toggleCart(false)
		}
	})

	// Prevent the cart from closing when hovering on the preview
	cartPreview.addEventListener('mouseenter', function () {
		toggleCart(true)
	})

	// Handle clicking outside to close the cart
	document.addEventListener('click', function (e) {
		if (isCartOpen && !cartContainer.contains(e.target)) {
			toggleCart(false)
		}
	})

	// Handle quantity buttons
	const quantityBtns = document.querySelectorAll('.quantity-btn')
	quantityBtns.forEach((btn) => {
		btn.addEventListener('click', function (e) {
			e.stopPropagation() // Prevent closing the cart
			const input = this.parentElement.querySelector('.quantity-input')
			let value = parseInt(input.value)

			if (this.textContent === '+') {
				value++
			} else if (this.textContent === '−' && value > 1) {
				value--
			}

			input.value = value
			// Here you would typically update the cart total
		})
	})

	// Handle remove buttons
	const removeButtons = document.querySelectorAll('.item-remove')
	removeButtons.forEach((btn) => {
		btn.addEventListener('click', function (e) {
			e.stopPropagation() // Prevent closing the cart
			const item = this.closest('.cart-preview-item')
			item.remove()

			// Update the cart count
			const itemCount = document.querySelectorAll('.cart-preview-item').length
			document.querySelector('.cart-count').textContent = itemCount

			// Here you would typically update the cart total
		})
	})
})
