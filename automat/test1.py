#! python
import so,sys
import unittest
from selenium import webdriver
from selenium.webdriver.common.keys import Keys

driver = webdriver.Chrome('./driver/chromedriver')

driver.get("https://www.python.org")

print(driver.title)
