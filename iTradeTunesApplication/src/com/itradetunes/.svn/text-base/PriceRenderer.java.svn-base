package com.itradetunes;

import javax.swing.*;
import javax.swing.table.*;
import java.math.BigDecimal;

import java.text.NumberFormat;

public class PriceRenderer extends DefaultTableCellRenderer {
  
  public PriceRenderer() { 
    setHorizontalAlignment(SwingConstants.RIGHT);  
  }
  
  public void setValue(Object aValue) {
    Object result = aValue;
    if (( aValue != null) && (aValue instanceof BigDecimal)) {
      Number numberValue = (Number)aValue;
      NumberFormat formatter = NumberFormat.getCurrencyInstance();
      result = formatter.format(numberValue.doubleValue());
    } 
    super.setValue(result);
  }   
} 
