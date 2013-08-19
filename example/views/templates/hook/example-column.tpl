<!-- Block example -->
<div id="example_block_left" class="block">
  <h4>Welcome!</h4>
  <div class="block_content">
    <p>Hello, 
       {if isset($example_name) && $example_name}
           {$example_name}
       {else}
           World
       {/if}
       !        
    </p>    
    <ul>
      <li><a href="{$example_link}" title="Click this link">Click me!</a></li>
    </ul>
  </div>
</div>
<!-- /Block example -->