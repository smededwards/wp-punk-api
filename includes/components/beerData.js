/**
 * Component: Beer Data
 * 
 * This component is used to get the data list of beers from the Custom Post Type
 */
import { select } from '@wordpress/data';

const BeerData = () => {
	// Create an empty array to store the beer data
	let beers = [];

	// Get the list of beers from the Custom Post Type
	const postType = 'beers';
	const beersList = select( 'core' ).getEntityRecords( 'postType', postType, {
		'per_page': -1,
		'orderby': 'title',
		'order': 'asc'
	});

	// Gutenberg editor get post featured image medium by id
	const getPostFeaturedImage = ( id ) => {
		const img = select( 'core' ).getMedia( id );
		if ( img ) {
			const sizes = img.media_details.sizes;
			const medium = sizes.medium;
			return medium.source_url;
		}
		return false;
	}

	// If there are beers, add them to the array
	if ( beersList ) {
		beersList.forEach( beer => {
			beers.push( {
				value: beer.id,
				label: beer.title.rendered,
				title: beer.title.rendered,
				description: beer.content.rendered,
				url: beer.link,
				img: getPostFeaturedImage( beer.featured_media )
			} );
		} );
	}

	// Remove HTML tags from the description
	beers.forEach( beer => {
		beer.description = beer.description.replace( /(<([^>]+)>)/ig, '' );
	} );

	return {
		beersList: beers
	};
};

export default BeerData;
