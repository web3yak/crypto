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

	const { symbol, currency, style,color, size, theme } = props;
	return (
		<Fragment>
		<InspectorControls>, siz
			<PanelBody label={__('Token Price', 'crypto')}>
				
						<TextControl
					label={__('Token Symbol', 'crypto')}
					value={props.attributes.symbol}
					onChange={symbol => props.setAttributes({ symbol })}
				/>

<SelectControl
								label={ 'Currency' }
								value={ props.attributes.currency }
								options={ [
									{ label: 'US Dollar', value: 'USD' },
									{ label: 'Indian Rupees', value: 'INR' }
								] }
								onChange={ function( currency ) {
									props.setAttributes( { currency } );
								} }
							/>

<SelectControl
								label={ 'Style' }
								value={ props.attributes.style }
								options={ [
									{ label: 'Style 1', value: 'style1' },
									{ label: 'Style 2', value: 'style2' },
									{ label: 'None', value: '' }
								] }
								onChange={ function( style ) {
									props.setAttributes( { style } );
								} }
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

<SelectControl
								label={ 'Size' }
								value={ props.attributes.size }
								options={ [
									{ label: 'Small', value: 'fl-is-small' },
									{ label: 'Default', value: '' },
									{ label: 'Medium', value: 'fl-is-medium' },
									{ label: 'Large', value: 'fl-is-large' }
								] }
								onChange={ function( size ) {
									props.setAttributes( { size } );
								} }
							/>
							<SelectControl
								label={ 'Theme' }
								value={ props.attributes.theme }
								options={ [
									{ label: 'Dark', value: 'fl-is-dark' },
									{ label: 'Default', value: '' },
									{ label: 'Light', value: 'fl-is-light' }
								] }
								onChange={ function( theme ) {
									props.setAttributes( { theme } );
								} }
							/>
				
			
		

			</PanelBody>
		</InspectorControls>
		<div { ...useBlockProps() }>
			<ServerSideRender 
				block="create-block/token-price" attributes={props.attributes}
			/>	
		</div>
		</Fragment>
	);
}
