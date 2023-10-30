/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n"
import { registerBlockType } from "@wordpress/blocks"
import { PanelBody, PanelRow, SelectControl, Spinner } from "@wordpress/components"
import { Fragment, useEffect, useState } from "@wordpress/element"
import { InspectorControls } from "@wordpress/block-editor"
import { AlignmentToolbar, BlockControls } from "@wordpress/block-editor"

/**
 * Internal dependencies
 */
import BeerData from "../../components/beerData"

/**
 * Register block
 */
registerBlockType("wp-punk-api/beer-individual", {
	title: __( "Individual Beer", "wp-punk-api" ),
	description: __( "Display an individual beer from the Punk API", "wp-punk-api" ),
	category: "wp-punk-api",
	icon: "beer",
	keywords: ["beer", "individual", "punk api"],
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
		beerDescription: {
			type: "string",
			default: "",
		},
		beerImg: {
			type: "string",
			default: "",
		},
		beerUrl: {
			type: "string",
			default: "",
		},
		textAlign: {
			type: "string",
			default: "left",
		}
	},
	edit: ( props ) => {
		const { attributes, setAttributes } = props;
		const { beerId, beerName, beerDescription, beerImg, textAlign } = attributes;

		const beers = BeerData();
		const [ beerData, setBeerData ] = useState();
		const [ isLoading, setIsLoading ] = useState(true);
		// Set the text alignment
		const onTextAlignChange = ( textAlign ) => setAttributes( { textAlign } );

		// Set the beer data once it's loaded
		useEffect( () => { setBeerData( beers ); setIsLoading( false ); }, [ beers ] );

		// If the data is loading, display a message
		if ( isLoading && !beerData ) {
			return (
				<Fragment>
					<p>
						<Spinner />
						{ __( "Fetching the beers...", "wp-punk-api" ) }
					</p>
				</Fragment>
			);
		}

		// Set the default beer, wait for the data to load if it hasn't already
		if ( !beerId ) {
			setAttributes( {
				beerId: beerData.beersList[0].value,
				beerName: beerData.beersList[0].title,
				beerDescription: beerData.beersList[0].description,
				beerImg: beerData.beersList[0].img,
				beerUrl: beerData.beersList[0].url,
			} );
		}

		// Set the beer data when the beer it's changed
		const onBeerChange = ( beerId ) => {
			beerData.beersList.forEach( beer => {
				if ( beerId == beer.value ) {
					setAttributes( {
						beerId: beer.value,
						beerName: beer.title,
						beerDescription: beer.description,
						beerImg: beer.img,
						beerUrl: beer.url,
					} );
				}
			} );
		};

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( "Beer Settings", "wp-punk-api" ) }>
						<PanelRow>
							<SelectControl
								label={ __( "Select Beer", "wp-punk-api" ) }
								value={ beerId }
								options={ beerData.beersList }
								onChange={ onBeerChange }
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody title={ __( "Text Settings", "wp-punk-api" ) }>
						<PanelRow>
							<p>{ __( "Alignment", "wp-punk-api" ) }</p>
							<AlignmentToolbar
								value={ textAlign }
								onChange={ onTextAlignChange }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<BlockControls>
					<AlignmentToolbar
						value={ textAlign }
						onChange={ onTextAlignChange }
					/>
				</BlockControls>
				<div className="wp-punk-api__beer-individual">
					{ !beerName && <p>{ __( "Please select a beer...", "wp-punk-api" ) }</p> }
					<h2 className="wp-punk-api__beer-individual-title" style={ { textAlign: textAlign } }>{ beerName }</h2>
					{ beerImg && <img className="wp-punk-api__beer-individual-img" src={ beerImg } alt={ beerName } /> }
					<p className="wp-punk-api__beer-individual-description" style={ { textAlign: textAlign } }>{ beerDescription }</p>
				</div>
			</Fragment>
		);
	},
	save: ( props ) => {
		const { attributes } = props;
		const { beerName, beerDescription, beerImg, beerUrl, textAlign } = attributes;
		return (
			<div className="wp-punk-api__beer-individual">
				<a className="wp-punk-api__beer-individual-link" href={ beerUrl } style={ { textAlign: textAlign } }>
					<h2 className="wp-punk-api__beer-individual-title" style={ { textAlign: textAlign } }>{ beerName }</h2>
					{ beerImg && <img className="wp-punk-api__beer-individual-img" src={ beerImg } alt={ beerName } /> }
				</a>
				<p className="wp-punk-api__beer-individual-description" style={ { textAlign: textAlign } }>{ beerDescription }</p>
			</div>
		);
	}
} );
