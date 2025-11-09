<?php

/**
 * Test modern CSS selector support (:is, :where, etc.)
 *
 * @covers Less_Parser
 */
class ModernSelectorsTest extends LessTestCase {

	/**
	 * Test simple :is() selector
	 */
	public function testIsSelector() {
		$lessCode = '.test:is(.a, .b) { color: red; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.test:is(.a, .b)', $css );
		$this->assertStringContainsString( 'color: red;', $css );
	}

	/**
	 * Test :is() with nested :not()
	 */
	public function testIsWithNestedNot() {
		$lessCode = '.test:is(.a:not(.x), .b) { color: red; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.test:is(.a:not(.x), .b)', $css );
		$this->assertStringContainsString( 'color: red;', $css );
	}

	/**
	 * Test complex UIKit-style selector with multiple nested pseudo-classes
	 */
	public function testComplexUikitSelector() {
		$lessCode = '.uk-form-small:is(.uk-input, .uk-search-input, .uk-select:not([multiple]):not([size])) { height: 30px; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.uk-form-small:is', $css );
		$this->assertStringContainsString( ':not([multiple])', $css );
		$this->assertStringContainsString( ':not([size])', $css );
		$this->assertStringContainsString( 'height: 30px;', $css );
	}

	/**
	 * Test simple :where() selector
	 */
	public function testWhereSelector() {
		$lessCode = ':where(.test) { margin: 0; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( ':where(.test)', $css );
		$this->assertStringContainsString( 'margin: 0;', $css );
	}

	/**
	 * Test :where() with multiple selectors
	 */
	public function testWhereWithMultipleSelectors() {
		$lessCode = ':where(.a, .b, .c) { padding: 10px; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( ':where(.a, .b, .c)', $css );
		$this->assertStringContainsString( 'padding: 10px;', $css );
	}

	/**
	 * Test combining :is() and :where()
	 */
	public function testCombinedIsAndWhere() {
		$lessCode = '.parent:is(.a, .b):where(.x, .y) { padding: 10px; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.parent:is(.a, .b):where(.x, .y)', $css );
		$this->assertStringContainsString( 'padding: 10px;', $css );
	}

	/**
	 * Test modern selectors with LESS variables
	 */
	public function testWithLessVariables() {
		$lessCode = '@color: blue; @size: 20px; .test:is(.a, .b) { color: @color; font-size: @size; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.test:is(.a, .b)', $css );
		$this->assertStringContainsString( 'color: blue;', $css );
		$this->assertStringContainsString( 'font-size: 20px;', $css );
	}

	/**
	 * Test :has() selector
	 */
	public function testHasSelector() {
		$lessCode = '.parent:has(.child) { background: red; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.parent:has(.child)', $css );
		$this->assertStringContainsString( 'background: red;', $css );
	}

	/**
	 * Test :is() with attribute selectors
	 */
	public function testIsWithAttributes() {
		$lessCode = '.test:is([href], [src], [data-id]) { display: block; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.test:is([href], [src], [data-id])', $css );
		$this->assertStringContainsString( 'display: block;', $css );
	}

	/**
	 * Test :where() at the start of a selector
	 */
	public function testWhereAtStart() {
		$lessCode = ':where(h1, h2, h3) { font-weight: bold; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( ':where(h1, h2, h3)', $css );
		$this->assertStringContainsString( 'font-weight: bold;', $css );
	}

	/**
	 * Test deeply nested modern selectors
	 */
	public function testDeeplyNested() {
		$lessCode = '.a:is(.b:where(.c:not(.d)), .e) { margin: 5px; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.a:is(.b:where(.c:not(.d)), .e)', $css );
		$this->assertStringContainsString( 'margin: 5px;', $css );
	}

	/**
	 * Test :is() with child combinator inside
	 */
	public function testIsWithChildCombinator() {
		$lessCode = '.parent:is(.a > .b, .c) { color: green; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.parent:is(.a > .b, .c)', $css );
		$this->assertStringContainsString( 'color: green;', $css );
	}

	/**
	 * Test modern selectors in nested LESS rules
	 */
	public function testInNestedLess() {
		$lessCode = '.outer { .inner:is(.a, .b) { padding: 15px; } }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.outer .inner:is(.a, .b)', $css );
		$this->assertStringContainsString( 'padding: 15px;', $css );
	}

	/**
	 * Test :not() standalone (already supported, but verify it still works)
	 */
	public function testNotStandalone() {
		$lessCode = '.test:not(.excluded) { display: flex; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.test:not(.excluded)', $css );
		$this->assertStringContainsString( 'display: flex;', $css );
	}

	/**
	 * Test multiple :is() selectors in one rule
	 */
	public function testMultipleIs() {
		$lessCode = '.a:is(.b, .c), .d:is(.e, .f) { width: 100%; }';
		$parser = new Less_Parser();
		$parser->parse( $lessCode );
		$css = $parser->getCss();

		$this->assertStringContainsString( '.a:is(.b, .c)', $css );
		$this->assertStringContainsString( '.d:is(.e, .f)', $css );
		$this->assertStringContainsString( 'width: 100%;', $css );
	}

	/**
	 * Test case insensitivity of :IS() vs :is()
	 */
	public function testCaseInsensitivity() {
		$lessCode1 = '.test:is(.a, .b) { color: red; }';
		$lessCode2 = '.test:IS(.a, .b) { color: red; }';

		$parser1 = new Less_Parser();
		$parser1->parse( $lessCode1 );
		$css1 = $parser1->getCss();

		$parser2 = new Less_Parser();
		$parser2->parse( $lessCode2 );
		$css2 = $parser2->getCss();

		// Both should parse successfully
		$this->assertStringContainsString( 'color: red;', $css1 );
		$this->assertStringContainsString( 'color: red;', $css2 );
	}
}
