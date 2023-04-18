/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, useBlockProps,  RichText, AlignmentToolbar,  BlockControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { Fragment,useState } from '@wordpress/element';
import { PanelBody,TextControl,SelectControl } from '@wordpress/components';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {

	const { contract, symbol, image, title, css, type, color, size, theme } = props;

	return (
		<Fragment>
		<InspectorControls>, siz
			<PanelBody label={__('Add Token', 'crypto')}>
				
						<TextControl
					label={__('Button Title', 'crypto')}
					value={props.attributes.title}
					onChange={title => props.setAttributes({ title })}
				/>

<SelectControl
								label={ 'Color' }
								value={ props.attributes.color }
								options={ [
									{ label: 'Blue', value: 'fl-is-info' },
									{ label: 'Green', value: 'fl-is-success' },
									{ label: 'Yellow', value: 'fl-is-warning' },
									{ label: 'Red', value: 'fl-is-danger' },
									{ label: 'Black', value: 'fl-is-black' },
									{ label: 'White', value: 'fl-is-white' },
									{ label: 'Clear', value: '' },
								] }
								onChange={ function( color ) {
									props.setAttributes( { color } );
								} }
							/>
				
							<TextControl
					label={__('Class Name', 'crypto')}
					value={props.attributes.css}
					onChange={css => props.setAttributes({ css })}
				/>

			</PanelBody>
		</InspectorControls>
		<div { ...useBlockProps() }>
			<ServerSideRender 
				block="create-block/crypto-connect" attributes={props.attributes}
			/>	
		</div>
		</Fragment>
	);
}
