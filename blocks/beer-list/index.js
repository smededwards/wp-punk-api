/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n"
import { registerBlockType } from "@wordpress/blocks"
import { Fragment, useEffect, useState } from "@wordpress/element"

/**
 * Internal dependencies
 */
import BeerData from "../../components/beerData"
import beerListIcon from "./beer-list-icon"

/**
 * Register block
 */
registerBlockType("wp-punk-api/beer-list", {
	title: __( "Beer List", "wp-punk-api" ),
	description: __( "Display a list of beers from the Punk API", "wp-punk-api" ),
	category: "wp-punk-api",
	icon: beerListIcon,
	keywords: ["beer", "list", "punk api"],
	supports: {
		html: true,
	},
	attributes: {
		beerId: {
			type: "number",
			default: 1,
		},
		beerName: {
			type: "string",
			default: "",
		},
	},
	edit: () => {
		const beers = BeerData();
		const [ beerData, setBeerData ] = useState();
		const [ isLoading, setIsLoading ] = useState(true);

		useEffect( () => { setBeerData( beers ); setIsLoading( false ); }, [] );

		// If the data is loading, display a message
		if ( isLoading ) {
			return (
				<Fragment>
					<p>{ __( "Fetching the beers...", "wp-punk-api" ) }</p>
				</Fragment>
			);
		}

		// If data is returned, display the list
		if ( beerData ) {
			return (
				<Fragment>
					<ul className="wp-punk-api__beer-list">
						{ beerData.beersList.map( ( beer ) => {
							return (
								<li key={ beer.value } className="wp-punk-api__beer-list-item">
									{ beer.label }
								</li>
							);
						})}
					</ul>
				</Fragment>
			);
		}
	},

	save: () => {
		const beers = BeerData();

		return (
			<ul className="wp-punk-api__beer-list">
				{ beers.beersList.map( ( beer ) => {
					return (
						<li key={ beer.value } className="wp-punk-api__beer-list-item">
							<a href={ beer.url }>
								{ beer.label }
							</a>
						</li>
					);
				} ) }
			</ul>
		);
	},
} );
