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
import { Fragment } from '@wordpress/element';
import { PanelBody,TextControl } from '@wordpress/components';
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

	const { contract, symbol, image, title, css, type } = props;
	return (
		<Fragment>
		<InspectorControls>
			<PanelBody label={__('Add Token', 'crypto')}>
				<TextControl
					label={__('Contract Address', 'crypto')}
					value={props.attributes.contract}
					onChange={contract => props.setAttributes({ contract })}
				/>
				<TextControl
					label={__('symbol', 'crypto')}
					value={props.attributes.symbol}
					onChange={symbol => props.setAttributes({ symbol })}
				/>

					<TextControl
					label={__('Image icon URL', 'crypto')}
					value={props.attributes.image}
					onChange={image => props.setAttributes({ image })}
				/>
						<TextControl
					label={__('Button Title', 'crypto')}
					value={props.attributes.title}
					onChange={title => props.setAttributes({ title })}
				/>
						<TextControl
					label={__('Blockchain Type', 'crypto')}
					value={props.attributes.type}
					onChange={type => props.setAttributes({ type })}
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
				block="create-block/add-network" attributes={props.attributes}
			/>	
		</div>
		</Fragment>
	);
}
