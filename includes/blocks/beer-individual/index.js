/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n"
import { registerBlockType } from "@wordpress/blocks"
import { withSelect } from "@wordpress/data"
import { Fragment } from "@wordpress/element"
import { InspectorControls } from "@wordpress/block-editor"
import { PanelBody, PanelRow, SelectControl } from "@wordpress/components"

/**
 * Register block
 */
registerBlockType("wp-punk-api/beer-individual", {
	title: __( "Individual Beer", "wp-punk-api" ),
	description: __( "Display an individual beer from the Punk API", "wp-punk-api" ),
	category: "custom",
	icon: "beer",
	keywords: ["beer", "punk api"],
	supports: {
		html: false,
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
	},
	edit: withSelect( ( select ) => {
		let options = [];
		const postType = 'beers'
		const beersList = select( 'core' ).getEntityRecords( 'postType', postType, { 
				'per_page': -1,
				'orderby': 'title',
				'order': 'asc',
			}
		);

		if ( beersList ) {
			beersList.forEach( ( beer ) => {
				options.push( {
					value: beer.id,
					label: beer.title.rendered,
					title: beer.title.rendered,
					content: beer.content.rendered,
				} );
			} );
		}

		options.forEach( ( beer ) => {
			beer.content = beer.content.replace( /(<([^>]+)>)/gi, "" );
		} );

		return {
			beersList: options,
		};

	} )( ( props ) => {
		const { attributes, setAttributes, beersList } = props;
		let { beerId, beerName, beerDescription } = attributes;

		const onChangeBeer = ( value ) => {
			const beer = beersList.find( ( beer ) => beer.value === parseInt( value ) );
			setAttributes( {
				beerId: parseInt( value ),
				beerName: beer.title,
				beerDescription: beer.content,
			} );
		};

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( "Settings", "wp-punk-api" ) }>
						<PanelRow>
							<SelectControl
								label={ __( "Select a beer", "wp-punk-api" ) }
								value={ beerId }
								options={ beersList }
								onChange={ onChangeBeer }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<section className={ props.className }>
					<span className="dashicons dashicons-beer"></span>
					<p>Beer ID: { beerId }</p>
					<p>Beer Name: { beerName }</p>
					<p>Beer Description: { beerDescription }</p>
				</section>
			</Fragment>
		);
	} ),

	save: ( props ) => {
		const { attributes } = props;
		const { beerId, beerName, beerDescription } = attributes;

		return (
			<section className={ props.className }>
				<span className="dashicons dashicons-beer"></span>
				<p>Beer ID: { beerId }</p>
				<p>Beer Name: { beerName }</p>
				<p>Beer Description: { beerDescription }</p>
			</section>
		)
	}
} );
