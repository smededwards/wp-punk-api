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

	// If there are beers, add them to the array
	if ( beersList ) {
		beersList.forEach( beer => {
			beers.push( {
				value: beer.id,
				label: beer.title.rendered,
				title: beer.title.rendered,
				description: beer.content.rendered,
				url: beer.link,
			} );
		} );
	}

	beers.forEach( beer => {
		beer.label = beer.title;
		beer.title = beer.title;
		beer.description = beer.description.replace( /(<([^>]+)>)/ig, '' );
	} );

	return {
		beersList: beers
	};
};

export default BeerData;
