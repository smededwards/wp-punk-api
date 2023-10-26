/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n"
import { registerBlockType } from "@wordpress/blocks"
import { Fragment, RawHTML, useEffect, useState } from "@wordpress/element"

/**
 * Internal dependencies
 */
import BeerData from "../../components/beerData"

/**
 * Register block
 */
registerBlockType("wp-punk-api/beer-list", {
	title: __( "Beer List", "wp-punk-api" ),
	description: __( "Display a list of beers from the Punk API", "wp-punk-api" ),
	category: "custom",
	icon: "beer",
	keywords: ["beer", "punk api"],
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
		const nameSpace = "wp-punk-api";
		const [ isLoading, setIsLoading ] = useState(true);

		// Set the beer data
		const [ beerData, setBeerData ] = useState();
		useEffect( () => {
			setBeerData( beers );
			setIsLoading( false );
		}, [ beers ] );

		if ( isLoading ) {
			return (
				<Fragment>
					<p>{ __( "Fetching the beers...", nameSpace ) }</p>
				</Fragment>
			);
		}

		// if no data is returned, display a message
		if ( !beerData ) {
			return (
				<Fragment>
					<p>{ __( "No beers found.", nameSpace ) }</p>
				</Fragment>
			);
		}

		// Q: Why am I getting this error? Cannot read properties of undefined (reading 'replace')
		// A: The error is caused by the fact that the beer content is not being returned in the beer data.

		if ( beerData ) {
			return (
				<Fragment>
					<ul className="wp-punk-api__beer-list">
						{ beerData.beersList.map( ( beer ) => {
							return (
								<li key={ beer.value } className="wp-punk-api__beer-list-item">
									<RawHTML>
										{ beer.label }
									</RawHTML>
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
