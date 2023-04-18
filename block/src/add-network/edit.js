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

	const { title, name, chainid, currency, symbol, rpcurl, explorer, css } = props;
	return (
		<Fragment>
		<InspectorControls>
			<PanelBody label={__('Add Network', 'crypto')}>
				<TextControl
					label={__('Title', 'crypto')}
					value={props.attributes.title}
					onChange={title => props.setAttributes({ title })}
				/>
					<TextControl
					label={__('Name', 'crypto')}
					value={props.attributes.name}
					onChange={name => props.setAttributes({ name })}
				/>
						<TextControl
					label={__('chainid', 'crypto')}
					value={props.attributes.chainid}
					onChange={chainid => props.setAttributes({ chainid })}
				/>
						<TextControl
					label={__('currency', 'crypto')}
					value={props.attributes.currency}
					onChange={currency => props.setAttributes({ currency })}
				/>
						<TextControl
					label={__('symbol', 'crypto')}
					value={props.attributes.symbol}
					onChange={symbol => props.setAttributes({ symbol })}
				/>
						<TextControl
					label={__('rpcurl', 'crypto')}
					value={props.attributes.rpcurl}
					onChange={rpcurl => props.setAttributes({ rpcurl })}
				/>
						<TextControl
					label={__('explorer', 'crypto')}
					value={props.attributes.explorer}
					onChange={explorer => props.setAttributes({ explorer })}
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
				block="create-block/add-token" attributes={props.attributes}
			/>	
		</div>
		</Fragment>
	);
}
